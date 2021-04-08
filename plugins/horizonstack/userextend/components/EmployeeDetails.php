<?php namespace Horizonstack\Userextend\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\Event;
use Illuminate\Support\Facades\Redirect;
use RainLab\User\Models\User;

class EmployeeDetails extends ComponentBase
{
    public $employee;

    public function componentDetails()
    {
        return [
            'name'        => 'Employee Details',
            'description' => 'Render all details about employee.',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $id = $this->param('id');

        $this->employee = User::with([
            'avatar',
            'jobLevel',
            'department',
            'subDepartment',
        ])->find($id);
    }

    public function onRun()
    {
        if (empty($this->employee)) {
            return Redirect::to('404');
        }

        $this->page['employee'] = $this->employee;
    }
}
