<?php namespace Horizonstack\eSPJ\Models;

use Model;
use RainLab\User\Models\User;

/**
 * Model
 */
class RealityForm extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'horizonstack_espj_reality_forms';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'trip'               => 'required',
        'user_id'            => 'required',
        'signature_left_id'  => 'required',
        'signature_right_id' => 'required',
    ];

    public $fillable = [
        'trip_id',
        'user_id',
        'transportation_id',
        'signature_left_id',
        'signature_right_id',
        'remarks',
    ];

    public $belongsTo = [
        'user'            => User::class,
        'trip'            => Trip::class,
        'signature_left'  => [
            Signature::class,
            'key' => 'signature_left_id',
        ],
        'signature_right' => [
            Signature::class,
            'key' => 'signature_right_id',
        ],
    ];

    public function getUserIdOptions()
    {
        $usersList = [null => "Select User"];

        $users = User::orderBy('name')->select('id', 'name')->get();

        foreach ($users as $user) {
            $usersList[$user->id] = $user->name;
        }

        return $usersList;
    }

    public function getTransportationIdOptions()
    {
        $transportationList = [null => "Select Transportation"];

        $transportations = Transportation::orderBy('name')->select('id', 'name')->get();

        foreach ($transportations as $transportation) {
            $transportationList[$transportation->id] = $transportation->name;
        }

        return $transportationList;
    }

    public function getSignatureLeftIdOptions()
    {
        $signaturesList = [null => "Select Signature"];

        $signatures = Signature::orderBy('name')->select('id', 'name')->get();

        foreach ($signatures as $signature) {
            $signaturesList[$signature->id] = $signature->name;
        }

        return $signaturesList;
    }

    public function getSignatureRightIdOptions()
    {
        $signaturesList = [null => "Select Signature"];

        $signatures = Signature::orderBy('name')->select('id', 'name')->get();

        foreach ($signatures as $signature) {
            $signaturesList[$signature->id] = $signature->name;
        }

        return $signaturesList;
    }
}
