<?php namespace Horizonstack\Investment\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Investment\Models\Investment;
use Horizonstack\Investment\Models\InvestmentInterest;
use Illuminate\Support\Facades\Redirect;
use Validator;
use ValidationException;
use Flash;

class InvestmentDetails extends ComponentBase
{
    public $investment;

    public $months = [
        1  => 'January',
        2  => 'February',
        3  => 'March',
        4  => 'April',
        5  => 'May',
        6  => 'June',
        7  => 'July',
        8  => 'August',
        9  => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    public function componentDetails()
    {
        return [
            'name'        => 'Investment Details',
            'description' => 'Render all details about Investment.',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $this->addCss('/themes/smartoffice/assets/css/investment-print.css');

        $slug = $this->param('slug');

        $this->investment = Investment::with([
            'photos',
        ])->where('slug', $slug)->first();
    }

    public function onRun()
    {
        if (empty($this->investment)) {
            return Redirect::to('404');
        }

        $this->page['investment'] = $this->investment;

        if ( ! empty($this->investment->city)) {
            $relatedInvestments = Investment::where('id', '!=', $this->investment->id)->where('city_id',
                $this->investment->city_id)->orderBy('created_at',
                'desc')->get();

            $this->page['relatedInvestments'] = $relatedInvestments;
        }

        if ( ! empty($this->investment->target_operation)) {
            $this->getMonthYear($this->investment->target_operation);
        }

    }

    public function getMonthYear($targetOperation)
    {
        $targetOperation = explode('/', $targetOperation);
        if ( ! empty($targetOperation[0])) {
            if ($targetOperation[0] != '10') {
                $targetOperation[0] = str_replace("0", "", $targetOperation[0]);
            }

            if (in_array($targetOperation[0], array_keys($this->months))) {
                $this->page['targetOperationMonth'] = $this->months[$targetOperation[0]];
            }
        }

        if ( ! empty($targetOperation[1])) {
            $this->page['targetOperationYear'] = $targetOperation[1];
        }
    }

    public function onSubmitNotifyMe()
    {
        $investmentId = post('investment_id');
        if ( ! empty($investmentId)) {
            $data = post();

            $rules = [
                'name'  => 'required',
                'email' => 'required|email|unique:horizonstack_investment_investment_interests',
            ];

            $messages = [
                'email.unique' => 'This email has already subscribed.',
            ];

            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            $interestedGuy = new InvestmentInterest();
            $interestedGuy->fill($data);
            $interestedGuy->save();

            Flash::success('You will be notified in future.');
        }
    }
}
