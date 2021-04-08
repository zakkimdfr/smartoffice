<?php namespace Horizonstack\Userextend;

use Backend\Facades\Backend;
use Horizonstack\Userextend\Components\DashboardStats;
use Horizonstack\Userextend\Components\EmployeeDetails;
use Horizonstack\Userextend\Components\ListEmployees;
use Horizonstack\Userextend\Components\UserAccount;
use Horizonstack\Userextend\Components\UserResetPassword;
use Horizonstack\Userextend\Components\UserSession;
use Horizonstack\Userextend\Components\UserUsedAssetList;
use Horizonstack\Userextend\Models\Department;
use Horizonstack\Userextend\Models\JobLevel;
use Horizonstack\Userextend\Models\SubDepartment;
use System\Classes\PluginBase;
use RainLab\User\Models\User as UserModel;
use RainLab\User\Controllers\Users as UsersController;
use Yaml;
use File;
use App;
use Config;
use Event;

class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['Rainlab.User'];

    public function registerComponents()
    {
        return [
            UserAccount::class       => 'userAccount',
            UserSession::class       => 'userSession',
            UserResetPassword::class => 'userResetPassword',
            DashboardStats::class    => 'dashboardStats',
            ListEmployees::class     => 'listEmployees',
            EmployeeDetails::class   => 'employeeDetails',
            UserUsedAssetList::class => 'userUsedAssetList',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Smart Office settings',
                'description' => 'Credentials and other settings for Smart Office.',
                'category'    => 'horizonstack.userextend::lang.plugin.name',
                'icon'        => 'icon-map-o',
                'class'       => 'Horizonstack\Userextend\Models\Setting',
                'order'       => 600,
                'permissions' => ['horizonstack.userextend.manage_settings'],
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Horizonstack\Userextend\FormWidgets\UserAddressFinder' => [
                'label' => 'User Address Finder',
                'code'  => 'user_addressfinder',
            ],
        ];
    }

    public function boot()
    {
        $this->extendUserModel();
        $this->extendUsersController();

        Event::listen('backend.menu.extendItems', function ($manager) {
            $manager->addSideMenuItems('RainLab.User', 'user', [
                'departments'    => [
                    'label'       => 'Departments',
                    'icon'        => 'icon-tasks',
                    'code'        => 'departments',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('horizonstack/userextend/departments'),
                    'permissions' => ['horizonstack.userextend.manage_departments'],
                ],
                'subdepartments' => [
                    'label'       => 'Sub Departments',
                    'icon'        => 'icon-braille',
                    'code'        => 'subdepartments',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('horizonstack/userextend/subdepartments'),
                    'permissions' => ['horizonstack.userextend.manage_sub_departments'],
                ],
                'joblevels'      => [
                    'label'       => 'Job Levels',
                    'icon'        => 'icon-suitcase',
                    'code'        => 'joblevels',
                    'owner'       => 'RainLab.User',
                    'url'         => Backend::url('horizonstack/userextend/joblevels'),
                    'permissions' => ['horizonstack.userextend.manage_job_levels'],
                ],
            ]);
        });
    }

    protected function extendUserModel()
    {


        UserModel::extend(function ($model) {
            $model->addFillable([
                'unique_id',
                'department_id',
                'sub_department_id',
                'job',
                'job_level_id',
                'date_of_birth',
                'phone',
                'address',
                'latitude',
                'longitude',
                'user_type',
                'is_profile_completed',
            ]);

            $model->addDynamicMethod('getUserTypeOptions', function () {
                return [
                    USER_TYPE_EMPLOYEE => 'Employee',
                    USER_TYPE_MANAGER  => 'Manager',
                ];
            });

            $model->addDynamicMethod('getDepartmentIdOptions', function () {
                $departmentsList = [null => "Select Department"];

                $departments = Department::orderBy('name')->select('id', 'name')->get();

                foreach ($departments as $department) {
                    $departmentsList[$department->id] = $department->name;
                }

                return $departmentsList;
            });

            $model->addDynamicMethod('getSubDepartmentIdOptions', function () use ($model) {

                if ( ! empty($model->department_id)) {
                    $subDepartmentsList = [null => "Select Sub Department"];

                    $subDepartments = SubDepartment::isActive()->where('department_id',
                        $model->department_id)->orderBy('name')->select('id', 'name')->get();

                    foreach ($subDepartments as $subDepartment) {
                        $subDepartmentsList[$subDepartment->id] = $subDepartment->name;
                    }
                } else {
                    $subDepartmentsList[null] = "Select Department First";
                }

                return $subDepartmentsList;
            });

            $model->addDynamicMethod('getJobLevelIdOptions', function () {
                $jobLevelsList = [null => "Select Job Level"];

                $jobLevels = JobLevel::orderBy('name')->select('id', 'name')->get();

                foreach ($jobLevels as $jobLevel) {
                    $jobLevelsList[$jobLevel->id] = $jobLevel->name;
                }

                return $jobLevelsList;
            });


            $model->belongsTo = [
                'department'    => [Department::class, 'key' => 'department_id'],
                'subDepartment' => [SubDepartment::class, 'key' => 'sub_department_id'],
                'jobLevel'      => [JobLevel::class, 'key' => 'job_level_id'],
            ];

            $model->bindEvent('model.beforeSave', function () use ($model) {
                if (empty($model->department_id)) {
                    $model->department_id     = null;
                    $model->sub_department_id = null;
                }

                if (empty($model->job_level_id)) {
                    $model->job_level_id = null;
                }

                if (empty($model->longitude)) {
                    $model->longitude = null;
                }

                if (empty($model->latitude)) {
                    $model->latitude = null;
                }
            });
        });
    }

    protected function extendUsersController()
    {
        UsersController::extendFormFields(function ($widget) {
            // Prevent extending of related form instead of the intended User form
            if ( ! $widget->model instanceof UserModel) {
                return;
            }

            $configFile = plugins_path('horizonstack/userextend/userconfig/profile_fields.yaml');
            $config     = Yaml::parse(File::get($configFile));
            $widget->addTabFields($config);
        });
    }

    public function registerMailTemplates()
    {
        return [
            'horizonstack.userextend::mail.restore',
        ];
    }
}
