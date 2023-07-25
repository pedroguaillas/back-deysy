<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/pass', function () use ($router) {
    return Hash::make('Arieklon669');
});

// API AUTH
$router->group(['prefix' => 'api'], function () use ($router) {

    // Matches "/api/login
    $router->post('login', 'AuthController@api_login');

    // Matches "/api/refreshtoken
    $router->get('refreshtoken', 'AuthController@refreshToken');

    $router->group(['middleware' => 'jwt.verify'], function () use ($router) {

        // Logout
        $router->get('logout', 'AuthController@logout');

        //Dashboard
        $router->get('dashboard', 'DashboardController@index');

        // Usado para: Registrar asesores
        $router->post('register', ['uses' => 'AuthController@register']);

        // asesores - users
        // Usado para: componentDidMount, reloadPage, onChangeSearch
        $router->post('listusser', ['uses' => 'UserController@listusser']);
        // Usado para ver la lista de asesores con su monto salarial componentDidMount, reloadPage, onChangeSearch
        $router->post('users/salaries', ['uses' => 'UserController@listusersalaries']);
        // Usado para: show user by Id
        $router->get('user/{id}', 'UserController@show');
        // Usado para: traer los clientes con los pagos
        $router->post('user/{id}/customers', 'UserController@customers');
        // Usado para: traer los clientes con los pagos
        $router->get('user/{id}/customerswidthcross', 'UserController@customerswidthcross');
        // Usado para: editar usuario
        $router->put('user/{id}', 'UserController@update');
        // Usado para: ver las lista de pagos por clientes en pdf
        $router->get('customerpdf/{id}', 'UserController@customerpdf');

        //Customers
        // Usado para: componentDidMount, reqNewPage, reloadPage, onChangeSearch
        $router->post('customerlist', 'ClienteAuditwholeController@customerlist');
        // Usado para: post
        $router->post('customers', ['uses' => 'ClienteAuditwholeController@store']);
        // Usado para: update
        $router->put('customers/{ruc}/update', ['uses' => 'ClienteAuditwholeController@update']);
        // Usado para: show
        $router->get('customers/{ruc}/show', 'ClienteAuditwholeController@show');
        // Usado para: delete
        $router->delete('customers/{ruc}/delete', 'ClienteAuditwholeController@destroy');
        // Usado para: payments
        $router->get('customers/{ruc}/payments', 'ClienteAuditwholeController@payments');

        //Payments
        $router->post('paymentlist', 'PaymentController@paymentlist');
        // Usado para almacenar el pago
        $router->post('payments', 'PaymentController@store');
        // Usado para editar el pago
        $router->put('payments/{id}', 'PaymentController@update');
        // Usado para eliminar el pago
        $router->delete('payments/{id}', 'PaymentController@destroy');
        // Usado para traer los pagos del cliente
        $router->get('custom/{ruc}/paymentcross', 'PaymentController@paymentcross');

        // Salaries
        // Usado para: componentDidMount, reqNewPage, reloadPage, onChangeSearch
        $router->post('salarylist', 'SalaryController@salarylist');
        $router->post('salaries', 'SalaryController@store');
        $router->put('salaries/{id}', 'SalaryController@update');
        $router->delete('salaries/{id}', 'SalaryController@destroy');

        // SalaryAdvance
        $router->get('salaryadvances/{salary_id}', 'SalaryAdvanceController@list');
        $router->post('salaryadvances', 'SalaryAdvanceController@store');
        $router->put('salaryadvances/{id}', 'SalaryAdvanceController@update');
        $router->delete('salaryadvances/{id}', 'SalaryAdvanceController@destroy');

        // SalaryAdvanceOfPay
        $router->post('salaryadvanceofpays', 'SalaryAdvanceOfPayController@store');
        $router->put('salaryadvanceofpays/{id}', 'SalaryAdvanceOfPayController@update');
        $router->delete('salaryadvanceofpays/{id}', 'SalaryAdvanceOfPayController@destroy');

        // Gastos
        $router->get('expenses', 'ExpenseController@index');
        $router->post('expenses', 'ExpenseController@store');
        $router->put('expenses/{id}', 'ExpenseController@update');
        $router->delete('expenses/{id}', 'ExpenseController@destroy');

        // Gasto Items
        $router->get('expense/{id}/items', 'ExpenseItemController@index');
        $router->post('expenseitems', 'ExpenseItemController@store');
        $router->put('expenseitems/{id}', 'ExpenseItemController@update');
        $router->delete('expenseitems/{id}', 'ExpenseItemController@destroy');

        //Dashboard
        $router->post('summary', 'SummaryController@index');
    });
});

// API REQUEST
// $router->group(['middleware' => 'jwt.verify', 'prefix' => 'api'], function ($router) {
// });

$router->get('tests', 'TestController@index');
$router->post('tests/downloading', ['uses' => 'TestController@downloading']);

$router->get('vdownloads', 'VDownloadController@index');
$router->post('vdownloads/downloading', ['uses' => 'VDownloadController@downloading']);

$router->post('login', ['uses' => 'AuthController@login']);

//Ruta que genera el ATS
$router->get('archivos', ['uses' => 'AtsController@index']);
$router->get('newarchivos', ['uses' => 'GenerateController@index']);

//Guardar archivo
$router->post('archivos', ['uses' => 'ArchivoController@store']);
$router->get('archivos/show', ['as' => 'show', 'uses' => 'ArchivoController@show']);
$router->get('archivos/showtype', ['as' => 'showtype', 'uses' => 'ArchivoController@showtype']);

$router->post('contactos', ['uses' => 'ContactoController@update']);
$router->post('contactosgetmasive', ['uses' => 'ContactoController@getmasive']);
$router->post('contactos/store', ['uses' => 'ContactoController@store']);
$router->get('contactos/{id}', 'ContactoController@show');

$router->post('reportcompra', ['uses' => 'ReportComprasController@report']);

$router->post('reportventa', ['uses' => 'ReportVentasController@report']);

// Usado para: update desde Fichart ATS
$router->post('customers/{ruc}/updatefichart', ['uses' => 'ClienteAuditwholeController@updatefichart']);
