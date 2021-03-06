<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;
use Illuminate\Validation\Rule;

class Hcp extends Model
{

  protected $fillable = [
    'code','name','address', 'account_number','email', 'cbn_code','phone', 'account_name','account_type', 'hcp_type_id','country_id', 'state_id', 'lga_id','town_id'
  ];


  public function users()
  {
    return $this->hasMany(HcpUser::class);
  }

  public function bank()
  {
    return $this->belongsTo(Bank::class, 'cbn_code', 'cbn_code');
  }

  public function town()
  {
    return $this->belongsTo(Town::class);
  }

  public function country()
  {
    return $this->belongsTo(Country::class);
  }

  public function state()
  {
    return $this->belongsTo(State::class);
  }

  public function hcp_type()
  {
    return $this->belongsTo(HcpType::class);
  }

  public function lga()
  {
    return $this->belongsTo(Lga::class);
  }

  public static function creationValidator(array $data)
  {
    return Validator::make($data, [
      'code' => 'required|string|max:20|unique:hcps',
      'name' => 'required|string|max:45|unique:hcps',
      'address' => 'required|string|max:125',
      'country_id' => 'required|exists:countries,id',
      'hcp_type_id' => 'required|exists:hcp_types,id',
      'cbn_code' => 'required',
      'account_number' => 'required|string|max:11',
      'state_id' => 'required|exists:states,id',
      'lga_id' => 'required|exists:lgas,id',
      'town_id' => 'exists:towns,id',
      'email' => 'required|email|max:125|unique:hcps',
      'phone' => 'required|regex:/^\d{7,11}$/|max:15|unique:hcps'
    ]);
  }

  public static function updateValidator(array $data, $id)
  {
    return Validator::make($data, [
      'address' => 'string|max:125',
      'name' => 'string|max:45',
      'cbn_code' => 'required',
      'account_number' => 'required|string|max:11',
      'country_id' => 'exists:countries,id',
      'hcp_type_id' => 'required|exists:hcp_types,id',
      'state_id' => 'exists:states,id',
      'lga_id' => 'exists:lgas,id',
      'town_id' => 'exists:towns,id',
      'code' => [
        'string',
        'max:20',
        Rule::unique('hcps')->ignore($id)
      ],
      'email' => [
        'email',
        'max:125',
        Rule::unique('hcps')->ignore($id)
      ],
      'phone' => [
        'regex:/^\d{7,11}$/',
        'max:15',
        Rule::unique('hcps')->ignore($id)
      ]
    ]);
  }
}
