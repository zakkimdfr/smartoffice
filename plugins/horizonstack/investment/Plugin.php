<?php namespace Horizonstack\Investment;

use Horizonstack\Investment\Components\InvestmentDetails;
use Horizonstack\Investment\Components\InvestmentForm;
use Horizonstack\Investment\Components\ListInvestments;
use System\Classes\PluginBase;
use Horizonstack\Investment\FormWidgets\InvestmentAddressFinder;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            ListInvestments::class   => 'listInvestments',
            InvestmentDetails::class => 'investmentDetails',
            InvestmentForm::class    => 'investmentForm',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerFormWidgets()
    {
        return [
            InvestmentAddressFinder::class => [
                'label' => 'Investment Address Finder',
                'code'  => 'investmentaddressfinder',
            ],
        ];
    }
}
