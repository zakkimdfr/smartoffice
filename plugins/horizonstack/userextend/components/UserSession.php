<?php namespace Horizonstack\Userextend\Components;

use Carbon\Carbon;
use Horizonstack\Userextend\Models\Department;
use Horizonstack\Userextend\Models\JobLevel;
use Horizonstack\Userextend\Models\Setting;
use Horizonstack\Userextend\Models\SubDepartment;
use Lang;
use Auth;
use Event;
use Flash;
use Input;
use NetSTI\Uploader\Components\ImageUploader;
use Request;
use Response;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\User\Models\UserGroup;
use System\Models\File;
use ValidationException;
use Validator;

class UserSession extends ComponentBase
{
    public $user;

    const ALLOW_ALL = 'all';
    const ALLOW_GUEST = 'guest';
    const ALLOW_USER = 'user';

    public function componentDetails()
    {
        return [
            'name'        => 'User Session',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [
            'security'          => [
                'title'       => 'rainlab.user::lang.session.security_title',
                'description' => 'rainlab.user::lang.session.security_desc',
                'type'        => 'dropdown',
                'default'     => 'all',
                'options'     => [
                    'all'   => 'rainlab.user::lang.session.all',
                    'user'  => 'rainlab.user::lang.session.users',
                    'guest' => 'rainlab.user::lang.session.guests',
                ],
            ],
            'allowedUserGroups' => [
                'title'       => 'rainlab.user::lang.session.allowed_groups_title',
                'description' => 'rainlab.user::lang.session.allowed_groups_description',
                'placeholder' => '*',
                'type'        => 'set',
                'default'     => [],
            ],
            'redirect'          => [
                'title'       => 'rainlab.user::lang.session.redirect_title',
                'description' => 'rainlab.user::lang.session.redirect_desc',
                'type'        => 'dropdown',
                'default'     => '',
            ],
        ];
    }

    public function getRedirectOptions()
    {
        return ['' => '- none -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getAllowedUserGroupsOptions()
    {
        return UserGroup::lists('name', 'code');
    }

    /**
     * Component is initialized.
     */
    public function init()
    {
        $this->page['google_map_api_key'] = Setting::get('google_maps_key');

        if (Request::ajax() && ! $this->checkUserSecurity()) {
            abort(403, 'Access denied');
        }

        $this->user = Auth::getUser();
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        if ( ! $this->checkUserSecurity()) {
            if (empty($this->property('redirect'))) {
                throw new \InvalidArgumentException('Redirect property is empty');
            }

            $redirectUrl = $this->controller->pageUrl($this->property('redirect'));

            return Redirect::guest($redirectUrl);
        }

        $this->page['user'] = $this->user;
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $departments                   = Department::isActive()->orderBy('name', 'asc')->get();
        $this->page['userDepartments'] = $departments;

        $jobLevels                   = JobLevel::isActive()->orderBy('name', 'asc')->get();
        $this->page['userJobLevels'] = $jobLevels;

        if ( ! empty($this->user)) {
            $subDepartments = SubDepartment::isActive()->where('department_id',
                $this->user->department_id)->orderBy('name')->get();

            $this->page['userSubDepartments'] = $subDepartments;
        }
    }

    public function onSelectDistrict()
    {
        $departmentId = post('department_id');

        $subDepartments = SubDepartment::isActive()->where('department_id', $departmentId)->orderBy('name')->get();

        $this->page['userSubDepartments'] = $subDepartments;
    }

    public function onUpdateProfile()
    {
        $data = post();

        $rules = [
            'name'              => 'required',
            'unique_id'         => 'required',
            'department_id'     => 'required',
            'sub_department_id' => 'required',
            'job_level_id'      => 'required',
            'job'               => 'required',
            'date_of_birth'     => 'required',
            'phone'             => 'required',
            'address'           => 'required',
        ];

        $messages = [
            'department_id.required'     => 'Please select Department.',
            'sub_department_id.required' => 'Please select Sub Department.',
            'job_level_id.required'      => 'Please select Job Level.',
        ];

        $validation = Validator::make($data, $rules, $messages);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        if ( ! $user = $this->user()) {
            return;
        }

        if ($data['date_of_birth']) {
            $data['date_of_birth'] = Carbon::createFromFormat('d/m/Y', $data['date_of_birth']);
        }

        $user->fill($data);
        $user->save();

        if ( ! empty(Input::file('avatar'))) {
            $avatar            = new File();
            $avatar->data      = Input::file('avatar');
            $avatar->is_public = true;
            $avatar->save();

            if ( ! empty($avatar)) {
                $user->avatar()->add($avatar);
            }
        } else {
            if (empty($user->avatar)) {
                throw new ValidationException(['avatar' => 'Please upload Avatar']);
            }
        }

        $is_profile_completed = 0;
        if (empty($data['name']) && empty($data['unique_id']) && empty($data['department_id'])
            && empty($data['sub_department_id']) && empty($data['job_level_id']) && empty($data['job'])
            && empty($data['date_of_birth']) && empty($data['phone']) && empty($data['address']) && empty($user->avatar)) {
            $is_profile_completed = 0;
        } else {
            $is_profile_completed = 1;
        }

        $user->is_profile_completed = $is_profile_completed;
        $user->save();

        Flash::success("Your profile is updated.");

        return Redirect::refresh();
    }

    /**
     * Returns the logged in user, if available, and touches
     * the last seen timestamp.
     * @return RainLab\User\Models\User
     */
    public function user()
    {
        if ( ! $user = Auth::getUser()) {
            return null;
        }

        if ( ! Auth::isImpersonator()) {
            $user->touchLastSeen();
        }

        return $user;
    }

    /**
     * Returns the previously signed in user when impersonating.
     */
    public function impersonator()
    {
        return Auth::getImpersonator();
    }

    /**
     * Log out the user
     *
     * Usage:
     *   <a data-request="onLogout">Sign out</a>
     *
     * With the optional redirect parameter:
     *   <a data-request="onLogout" data-request-data="redirect: '/good-bye'">Sign out</a>
     *
     */
    public function onLogout()
    {
        $user = Auth::getUser();

        Auth::logout();

        if ($user) {
            Event::fire('rainlab.user.logout', [$user]);
        }

        $url = post('redirect', Request::fullUrl());

        Flash::success(Lang::get('rainlab.user::lang.session.logout'));

        return Redirect::to($url);
    }

    /**
     * If impersonating, revert back to the previously signed in user.
     * @return Redirect
     */
    public function onStopImpersonating()
    {
        if ( ! Auth::isImpersonator()) {
            return $this->onLogout();
        }

        Auth::stopImpersonate();

        $url = post('redirect', Request::fullUrl());

        Flash::success(Lang::get('rainlab.user::lang.session.stop_impersonate_success'));

        return Redirect::to($url);
    }

    /**
     * Checks if the user can access this page based on the security rules
     * @return bool
     */
    protected function checkUserSecurity()
    {
        $allowedGroup      = $this->property('security', self::ALLOW_ALL);
        $allowedUserGroups = $this->property('allowedUserGroups', []);
        $isAuthenticated   = Auth::check();

        if ($isAuthenticated) {
            if ($allowedGroup == self::ALLOW_GUEST) {
                return false;
            }

            if ( ! empty($allowedUserGroups)) {
                $userGroups = Auth::getUser()->groups->lists('code');
                if ( ! count(array_intersect($allowedUserGroups, $userGroups))) {
                    return false;
                }
            }
        } else {
            if ($allowedGroup == self::ALLOW_USER) {
                return false;
            }
        }

        return true;
    }
}
