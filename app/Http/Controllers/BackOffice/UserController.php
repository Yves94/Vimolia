<?php

namespace App\Http\Controllers\BackOffice;

use App\ExpertUser;
use App\Http\Requests\UserRequest;
use App\PraticianUser;
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

    public function create(UserRequest $request) {

        dump($request->all());


        switch ($request->input('user_type')) {
            case "Public":
                $specialUser = new PublicUser();
                $dt = Carbon::createFromFormat('d/m/Y',$request->input('birthday'));
                $specialUser->birthday = $dt->year.'-'.str_pad($dt->month, 2, "0", STR_PAD_LEFT).'-'.$dt->day;

                dump($specialUser);

                //$public_user->save();
                break;
            case "Pratician":
                //$public_user = new PraticianUser();
                //$public_user->save();
                break;
            case "Expert":
                //$public_user = new ExpertUser();
                //$public_user->save();
                break;
        }
        $user = new User();
        $user->name = $request->input('name');
        $user->firstname = $request->input('firstname');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->password = bcrypt($request->input('password'));
        $user->userable_type = $request->input('user_type');
        //$user->userable_id = $public_user->id;
        //$user->save();

        $data['professions'] = DB::table('professions')->lists( 'profession','id');
        return view('BackOffice.user.create',$data);
    }

    /**
     * genere la table demandé en fonction du parametre get
     * @param $request
     * @return Table
     */
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
    /**
     * genere la table de tous type d'utilisateur
     * @return Table
     */
    public function usersTable()
    {
        $table = new Table('users');
        $table->setColumnDisplayed(['civility' => 'civilité', 'name' => 'nom', 'firstname' => 'prenom', 'userable_type_readable' => 'Type']);
        $table->prepareView();

        return $table;
    }

    /**
     * genere la table d' utilisateurs publique
     * @return Table
     */
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

    /**
     * genere la table d' utilisateurs praticien
     * @return Table
     */
    public function praticianUsersTable()
    {
        $table = new Table('users');
        $table->setColumnDisplayed(['civility' => 'civilité', 'name' => 'nom', 'firstname' => 'prenom', 'userable_type_readable' => 'Type', 'siret' => 'siret']);
        $table->addJoin(['pratician_users', 'pratician_users.id', '=', 'users.userable_id']);
        $table->addWhere(['userable_type_readable', '=', 'praticien']);
        $table->prepareView();

        return $table;
    }
    /**
     * genere la table d' utilisateurs expert
     * @return Table
     */
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

        /**
         * callback pour la recherche des dates
         */
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
