<?php

namespace App\Http\Controllers\BackOffice;

use App\PublicUser;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Laraveltable\Table\Table;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data['type'] = $request->input('type');
        $data['table'] = $this->prepareTable($request);

        return view('BackOffice.user.index', $data);
    }

    public function show($id)
    {
        $user = User::where('id', '=', $id)->firstOrFail();
        $user->load('addresses', 'userable');
        $data['user'] = $user;
        $data['address'] = $user->getMainAddress();
        return view('BackOffice.user.show', $data);
    }

    public function prepareTable($request)
    {
        switch ($request->input('type')) {
            case "publique":
                return $this->publicUsersTable();
                break;
            case "expert":
                return $this->expertUsersTable();
                break;
            case "praticien":
                return $this->praticianUsersTable();
                break;
            default :
                return $this->usersTable();
        }
    }

    public function usersTable()
    {
        $table = new Table('users');
        $table->setColumnDisplayed(['civility' => 'civilité', 'name' => 'nom', 'firstname' => 'prenom', 'userable_type_readable' => 'Type']);
        $table->prepareView();

        return $table;
    }

    public function publicUsersTable()
    {
        $table = new Table('users');
        $table->setColumnDisplayed(['civility' => 'civilité', 'name' => 'nom', 'firstname' => 'prenom', 'userable_type_readable' => 'Type', 'birthday' => 'Anniv']);
        $table->addJoin(['public_users', 'public_users.id', '=', 'users.userable_id']);
        $table->addWhere(['userable_type_readable', '=', 'publique']);
        $table = $this->callbackPublicUsers($table);
        $table->prepareView();

        return $table;
    }

    public function praticianUsersTable()
    {
        $table = new Table('users');
        $table->setColumnDisplayed(['civility' => 'civilité', 'name' => 'nom', 'firstname' => 'prenom', 'userable_type_readable' => 'Type', 'siret' => 'siret']);
        $table->addJoin(['pratician_users', 'pratician_users.id', '=', 'users.userable_id']);
        $table->addWhere(['userable_type_readable', '=', 'praticien']);
        $table->prepareView();

        return $table;
    }

    public function expertUsersTable()
    {
        $table = new Table('users');
        $table->setColumnDisplayed(['civility' => 'civilité', 'name' => 'nom', 'firstname' => 'prenom', 'userable_type_readable' => 'Type']);
        $table->addJoin(['expert_users', 'expert_users.id', '=', 'users.userable_id']);
        $table->addWhere(['userable_type_readable', '=', 'expert']);
        $table->prepareView();

        return $table;
    }

    public function callbackPublicUsers($table)
    {
        /**
         * callback pour qu'a l'affiche la date soit en francais
         */
        $table->addCallBackData('birthday', function ($data) {
            if(!empty($data)) {
                $carbon = new Carbon($data);
                $data = $carbon->format('d/m/Y');
            }
            return $data;
        });


        $table->addCallBackSearch('birthday', function ($data) {
            if (preg_match("^\\d{1,2}/\\d{2}/\\d{4}^",$data))
            {
                $data = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
            }

            return $data;
        });
        return $table;

    }
}
