<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;
class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'firstname' => 'required|max:255',
            'password' => 'required|max:255',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'email' => 'required|email',
            'user_type' => 'required|in:Pratician,Public,Expert',
        ];
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                switch ($_POST['user_type']) {
                    case "Public":
                        $rules['birthday'] = 'date|min_age:18';
                        break;
                    case "Pratician":
                        $rules['profession'] = 'required|integer';
                        $rules['siret'] = 'required|max:20';
                        $rules['degree'] = 'required|max:255';
                        break;
                    case "Expert":

                        break;
                }
                return $rules;

            }
        }
    }
}
