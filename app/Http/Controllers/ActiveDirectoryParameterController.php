<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActiveDirectoryParameterResource;
use App\Models\ActiveDirectoryParameter;
use App\Models\UserLdap;
use Exception;
use Illuminate\Http\Request;
use LdapRecord\{Connection, Container};
use Illuminate\Support\Facades\Crypt;


class ActiveDirectoryParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'company_id'      => 'required|numeric'
        ]);

        $param = ActiveDirectoryParameter::where('company_id', $request->company_id)->first();
        $response['parameter'] = isset($param) ? new ActiveDirectoryParameterResource($param) : null;
        $response['status'] = 200;
        $response['success'] = 1;
        return response()->json($response,  $response['status']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'hosts'           => 'required|string|between:3,60',
            'port'            => 'required|numeric|between:0,999999',
            'username'        => 'required|string|between:3,60',
            'password'        => 'required|string|between:1,60',
            'dc'              => 'required|string|between:3,90',
            'use_ssl'         => 'sometimes|boolean'
        ]);

        $activeDirectory = ActiveDirectoryParameter::where(['company_id' => $request->company_id])->first();
        if ($activeDirectory) {
            $activeDirectory->update([
                'hosts'      => Crypt::encryptString($request->hosts),
                'port'       => Crypt::encryptString($request->port),
                'username'   => Crypt::encryptString($request->username),
                'password'   => Crypt::encryptString($request->password),
                'dc'         => Crypt::encryptString($request->dc),
                'company_id' => $request->company_id,
                'use_ssl'   => isset($request->use_ssl) ? $request->use_ssl : false
            ]);
            $response['message'] = 'update active directory parameter';
            $response['status'] = 200;
            $response['success'] = 1;
        } else {
            $ad = ActiveDirectoryParameter::create([
                'hosts'      => Crypt::encryptString($request->hosts),
                'port'       => Crypt::encryptString($request->port),
                'username'   => Crypt::encryptString($request->username),
                'password'   => Crypt::encryptString($request->password),
                'dc'         => Crypt::encryptString($request->dc),
                'company_id' => $request->company_id,
                'use_ssl'   => isset($request->use_ssl) ? $request->use_ssl : false

            ]);
            $response['message'] = 'create active directory parameter';
            $response['AD'] = new ActiveDirectoryParameterResource($ad);
            $response['status'] = 200;
            $response['success'] = 2;
        }
        return response()->json($response,  $response['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
            'company_id'      => 'required|numeric'
        ]);
        $activeDirectory = ActiveDirectoryParameter::where(['company_id' => $request->company_id])->first();
        if ($activeDirectory) {
            $activeDirectory->delete();
            $response['message'] = 'Delete active directory parameter';
            $response['status'] = 200;
            $response['success'] = 1;
        } else {
            $response['message'] = 'Active directory parameter doesn\'t exist';
            $response['status'] = 400;
            $response['success'] = -1;
        }
        return response()->json($response,  $response['status']);
    }

    /**
     * Test connection to active directory.
     *
     * @return \Illuminate\Http\Response
     */
    public function testConnection(Request $request)
    {

        $this->validate($request, [
            'hosts'           => 'required|string|between:3,60',
            'port'            => 'required|numeric|between:0,999999',
            'username'        => 'required|string|between:3,60',
            'password'        => 'required|string|between:1,60',

        ]);
        try {

            $connection = new Connection([
                'hosts'    => [$request->hosts],
                'port'     => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'use_ssl'  => $request->use_ssl,
                'version'  => 3,
                'timeout' => 60
            ]);
            $connection->connect();
            Container::addConnection($connection);
            $ad = new \Adldap\Adldap();
            $ad->addProvider([
                'hosts'    => [$request->hosts],
                'port'     => $request->port,
                'username' => $request->username,
                'password' => $request->password,
                'use_ssl'  => $request->use_ssl,
                'version' => 3,
                'timeout' => 60
            ]);
            if (sizeof(UserLdap::in($request->dc)->where('objectClass', 'user')->get()) > 0) {
                $response['status'] = 200;
                $response['success'] = 1;
            } else {
                $response['status'] = 200;
                $response['success'] = -2;
            }
        } catch (Exception $e) {
            $response['status'] = 200;
            $response['msg'] = $e->getMessage();

            $response['success'] = -1;
        }
        return response()->json($response,  $response['status']);
    }
}
