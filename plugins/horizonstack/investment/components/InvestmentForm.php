<?php namespace Horizonstack\Investment\Components;

use Cms\Classes\ComponentBase;
use Horizonstack\Guestbook\Models\City;
use Horizonstack\Investment\Models\Infrastructure;
use Horizonstack\Investment\Models\Investment;
use Horizonstack\Investment\Models\InvestmentOrganization;
use Horizonstack\Investment\Models\Ownership;
use Horizonstack\Investment\Models\PolicyRegulation;
use Horizonstack\Investment\Models\ProjectLevel;
use Horizonstack\Investment\Models\PropertyType;
use Horizonstack\Investment\Models\Scheme;
use Horizonstack\Location\Models\District;
use Horizonstack\Location\Models\Village;
use Illuminate\Support\Facades\Redirect;
use NetSTI\Uploader\Components\ImageUploader;
use Flash;
use System\Models\File;
use ValidationException;
use Validator;
use Input;

class InvestmentForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Investment Form',
            'description' => 'Submit this form to create Investment',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function init()
    {
        $this->addCss('/themes/smartoffice/assets/css/custom-investment.css');

        $photos = $this->addComponent(
            ImageUploader::class,
            'photos',
            [
                'deferredBinding' => true,
                'fileTypes'       => ".gif,.jpg,.jpeg,.png",
                'imageMode'       => 'auto',
                'modelClass'      => Investment::class,
            ]
        );

        $photos->bindModel('photos', new Investment());
    }

    public function onRun()
    {
        $this->prepareVars();
    }

    public function prepareVars()
    {
        $this->page['propertyTypes']   = PropertyType::with('icon')->isActive()->orderBy('name')->get();
        $this->page['schemes']         = Scheme::with('icon')->isActive()->orderBy('name')->get();
        $this->page['projectLevels']   = ProjectLevel::isActive()->orderBy('name')->get();
        $this->page['cities']          = City::isActive()->orderBy('name', 'asc')->get();
        $this->page['ownerships']      = Ownership::isActive()->orderBy('name', 'asc')->get();
        $this->page['organizations']   = InvestmentOrganization::with('icon')->isActive()->orderBy('name',
            'asc')->get();
        $this->page['infrastructures'] = Infrastructure::with('icon')->isActive()->orderBy('name', 'asc')->get();
    }

    public function onSelectCity()
    {
        $city_id = post('city_id');

        $districts = District::where('city_id', $city_id)->orderBy('name')->get();

        $this->page['districts'] = $districts;
    }

    public function onSelectDistrict()
    {
        $district_id = post('district_id');

        $villages = Village::where('district_id', $district_id)->orderBy('name')->get();

        $this->page['villages'] = $villages;
    }

    public function onAddInvestment()
    {
        try {
            $data = post();

            $rules = [
                'name'              => 'required',
                'agent_name'        => 'required',
                'agent_phone'       => 'required',
                'organization_name' => 'required',
                'agent_email'       => 'required|email',
                'address'           => 'required',
                'city_id'           => 'required',
                'district_id'       => 'required',
                'village_id'        => 'required',
            ];

            $messages = [
                'organization_id.required' => 'Nama Organisasi dieprlukan',
            ];

            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            if ( ! empty($data['profitability_index'])) {
                $data['profitability_index'] = str_replace("%", "", $data['profitability_index']);
            }

            if ( ! empty($data['irr'])) {
                $data['irr'] = str_replace("%", "", $data['irr']);
            }
            
            if ( ! empty($data['bcr'])) {
                $data['bcr'] = str_replace("%", "", $data['bcr']);
            }
            
            if ( ! empty($data['return_of_investment'])) {
                $data['return_of_investment'] = str_replace("%", "", $data['return_of_investment']);
            }

            if ( ! empty($data['policy_regulations_name1']) || ! empty(Input::file('policy_regulations_file1'))) {
                if (empty($data['policy_regulations_name1'])) {
                    throw new ValidationException(['policy_regulations_name1' => "Please enter policy regulation name of 1."]);
                }
                if (empty(Input::file('policy_regulations_file1'))) {
                    throw new ValidationException(['policy_regulations_file1' => "Please upload policy regulation file of 1."]);
                }

                if ( ! empty(Input::file('policy_regulations_file1'))) {
                    $policy_regulations_file1            = new File();
                    $policy_regulations_file1->data      = Input::file('policy_regulations_file1');
                    $policy_regulations_file1->is_public = true;
                    $policy_regulations_file1->save();
                }
            }

            if ( ! empty($data['policy_regulations_name2']) || ! empty(Input::file('policy_regulations_file2'))) {
                if (empty($data['policy_regulations_name2'])) {
                    throw new ValidationException(['policy_regulations_name2' => "Please enter policy regulation name of 2."]);
                }
                if (empty(Input::file('policy_regulations_file2'))) {
                    throw new ValidationException(['policy_regulations_file2' => "Please upload policy regulation file of 2."]);
                }

                if ( ! empty(Input::file('policy_regulations_file2'))) {
                    $policy_regulations_file2            = new File();
                    $policy_regulations_file2->data      = Input::file('policy_regulations_file2');
                    $policy_regulations_file2->is_public = true;
                    $policy_regulations_file2->save();
                }
            }

            if ( ! empty($data['policy_regulations_name3']) || ! empty(Input::file('policy_regulations_file3'))) {
                if (empty($data['policy_regulations_name3'])) {
                    throw new ValidationException(['policy_regulations_name3' => "Please enter policy regulation name of 3."]);
                }
                if (empty(Input::file('policy_regulations_file3'))) {
                    throw new ValidationException(['policy_regulations_file3' => "Please upload policy regulation file of 3."]);
                }

                if ( ! empty(Input::file('policy_regulations_file3'))) {
                    $policy_regulations_file3            = new File();
                    $policy_regulations_file3->data      = Input::file('policy_regulations_file3');
                    $policy_regulations_file3->is_public = true;
                    $policy_regulations_file3->save();
                }
            }

            if ( ! empty($data['policy_regulations_name4']) || ! empty(Input::file('policy_regulations_file4'))) {
                if (empty($data['policy_regulations_name4'])) {
                    throw new ValidationException(['policy_regulations_name4' => "Please enter policy regulation name of 4."]);
                }
                if (empty(Input::file('policy_regulations_file4'))) {
                    throw new ValidationException(['policy_regulations_file4' => "Please upload policy regulation file of 4."]);
                }

                if ( ! empty(Input::file('policy_regulations_file4'))) {
                    $policy_regulations_file4            = new File();
                    $policy_regulations_file4->data      = Input::file('policy_regulations_file4');
                    $policy_regulations_file4->is_public = true;
                    $policy_regulations_file4->save();
                }
            }

            $investment = new Investment();
            $investment->fill($data);
            $investment->save(null, post('_session_key'));

            if ( ! empty($investment)) {
                if ( ! empty($data['investment_schemes']) && count($data['investment_schemes']) > 0) {
                    $investment->investment_schemes()->sync($data['investment_schemes']);
                }

                if ( ! empty($data['infrastructures']) && count($data['infrastructures']) > 0) {
                    $investment->infrastructures()->sync($data['infrastructures']);
                }

                if ( ! empty(Input::file('ded_document'))) {
                    $ded_document            = new File();
                    $ded_document->data      = Input::file('ded_document');
                    $ded_document->is_public = true;
                    $ded_document->save();

                    if ( ! empty($ded_document)) {
                        $investment->ded_document()->add($ded_document);
                    }
                }

                if ( ! empty(Input::file('amdal_document'))) {
                    $amdal_document            = new File();
                    $amdal_document->data      = Input::file('amdal_document');
                    $amdal_document->is_public = true;
                    $amdal_document->save();

                    if ( ! empty($amdal_document)) {
                        $investment->amdal_document()->add($amdal_document);
                    }
                }

                if ( ! empty(Input::file('amdas_document'))) {
                    $amdas_document            = new File();
                    $amdas_document->data      = Input::file('amdas_document');
                    $amdas_document->is_public = true;
                    $amdas_document->save();

                    if ( ! empty($amdas_document)) {
                        $investment->amdas_document()->add($amdas_document);
                    }
                }

                if ( ! empty(Input::file('feasibility_study_document'))) {
                    $feasibility_study_document            = new File();
                    $feasibility_study_document->data      = Input::file('feasibility_study_document');
                    $feasibility_study_document->is_public = true;
                    $feasibility_study_document->save();

                    if ( ! empty($feasibility_study_document)) {
                        $investment->feasibility_study_document()->add($feasibility_study_document);
                    }
                }

                if ( ! empty($policy_regulations_file1)) {
                    if ( ! empty($policy_regulations_file1)) {
                        $policyRegulationData = [
                            'name'          => $data['policy_regulations_name1'],
                            'investment_id' => $investment->id,
                        ];

                        $policyRegulation1 = new PolicyRegulation();
                        $policyRegulation1->fill($policyRegulationData);
                        $policyRegulation1->save();
                        $policyRegulation1->file()->add($policy_regulations_file1);
                    }

                    $investment->policyRegulations()->add($policyRegulation1);
                }

                if ( ! empty($policy_regulations_file2)) {
                    if ( ! empty($policy_regulations_file2)) {
                        $policyRegulationData = [
                            'name'          => $data['policy_regulations_name2'],
                            'investment_id' => $investment->id,
                        ];

                        $policyRegulation2 = new PolicyRegulation();
                        $policyRegulation2->fill($policyRegulationData);
                        $policyRegulation2->save();
                        $policyRegulation2->file()->add($policy_regulations_file2);
                    }

                    $investment->policyRegulations()->add($policyRegulation2);
                }

                if ( ! empty($policy_regulations_file3)) {
                    if ( ! empty($policy_regulations_file3)) {
                        $policyRegulationData = [
                            'name'          => $data['policy_regulations_name3'],
                            'investment_id' => $investment->id,
                        ];

                        $policyRegulation3 = new PolicyRegulation();
                        $policyRegulation3->fill($policyRegulationData);
                        $policyRegulation3->save();
                        $policyRegulation3->file()->add($policy_regulations_file3);
                    }

                    $investment->policyRegulations()->add($policyRegulation3);
                }

                if ( ! empty($policy_regulations_file4)) {
                    if ( ! empty($policy_regulations_file4)) {
                        $policyRegulationData = [
                            'name'          => $data['policy_regulations_name4'],
                            'investment_id' => $investment->id,
                        ];

                        $policyRegulation4 = new PolicyRegulation();
                        $policyRegulation4->fill($policyRegulationData);
                        $policyRegulation4->save();
                        $policyRegulation4->file()->add($policy_regulations_file4);
                    }

                    $investment->policyRegulations()->add($policyRegulation4);
                }
            }

            Flash::success('Investment created.');

            return Redirect::to('/investments');

        } catch (\Exception $exception) {
            if (\Illuminate\Support\Facades\Request::ajax()) {
                throw $exception;
            } else {
                Flash::error($exception->getMessage());
            }
        }
    }
}
