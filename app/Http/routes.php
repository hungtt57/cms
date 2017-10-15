<?php



Route::auth();

Route::group([
    'namespace' => 'Ajax',
    'prefix' => 'a',
    'as' => 'Ajax::',
], function () {
    Route::group([
        'middleware' => 'auth',
    ], function () {
        Route::group([
            'namespace' => 'Business',
            'prefix' => 'business',
            'as' => 'business@',
        ], function () {
            Route::post('ajax-get-attributes',['as' => 'ajaxGetAttributes' ,'uses' => 'BusinessController@ajaxGetAttributes']);
            Route::post('ajax-get-attributes-by-category',['as' => 'ajaxGetAttributesByCategory' ,'uses' => 'BusinessController@ajaxGetAttributesByCategory']);
            Route::post('ajax-get-attributes-by-edit-product-distributor',['as' => 'ajaxGetAttributesByEditProductDistributor' ,'uses' => 'BusinessController@ajaxGetAttributesByEditProductDistributor']);
        });
    });


    // Staff Ajax
    Route::group([
        'middleware' => 'auth:staff',
        'namespace' => 'Staff',
        'prefix' => 'staff',
        'as' => 'Staff::',
    ], function () {

        Route::group([
            'prefix' => 'notifications',
            'as' => 'notification@',
        ], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'NotificationController@index']);
        });

        Route::group([
            'namespace' => 'Management',
            'prefix' => 'management',
            'as' => 'Management::',
        ], function () {
            Route::group([
                'prefix' => 'businesses',
                'as' => 'business@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'BusinessController@index']);
                Route::post('/change-role', ['as' => 'changeRole', 'uses' => 'BusinessController@changeRole']);
            });

            Route::group([
                'prefix' => 'products',
                'as' => 'product@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
                Route::get('/destroy', ['as' => 'destroy', 'uses' => 'ProductController@destroy']);

                //inline attributes
                Route::post('/add-attr-inline', ['as' => 'addAttrInline', 'uses' => 'ProductController@addAttrInline']);
                Route::post('/update-attr-inline', ['as' => 'updateAttrInline', 'uses' => 'ProductController@updateAttrInline']);
                Route::post('/update-attr-contribute-product-inline', ['as' => 'updateAttrInlineContribute', 'uses' => 'ProductController@updateAttrInlineContribute']);
            });
                Route::group([
                'prefix' => 'business-distributor',
                'as' => 'businessDistributor@',
            ], function () {
                //inline attributes
                Route::post('/add-attr-inline', ['as' => 'addAttrInline', 'uses' => 'BusinessDistributorController@addAttrInline']);
                Route::post('/update-attr-inline', ['as' => 'updateAttrInline', 'uses' => 'BusinessDistributorController@updateAttrInline']);
            });

            Route::group([
                'prefix' => 'products2',
                'as' => 'product2@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'Product2Controller@index']);

                Route::post('/get-attr-by-category', ['as' => 'getAttributesByCategory', 'uses' => 'Product2Controller@getAttributesByCategory']);
                Route::post('/get-attr-by-product', ['as' => 'getAttributesByProduct', 'uses' => 'Product2Controller@getAttributesByProduct']);

                Route::post('/update-attr-inline', ['as' => 'updateAttrInline', 'uses' => 'Product2Controller@updateAttrInline']);
                Route::post('/add-attr-inline', ['as' => 'addAttrInline', 'uses' => 'Product2Controller@addAttrInline']);
                Route::post('/get-attr-id-category', ['as' => 'getAttrIdCategory', 'uses' => 'Product2Controller@getAttrIdCategory']);
            });
        });

        Route::group([
            'prefix' => 'upload',
            'as' => 'upload@',
        ], function () {
            Route::match(['put', 'post'], 'image', ['as' => 'image', 'uses' => 'UploadController@image']);
        });
    });
});


Route::group([
    'namespace' => 'Business',
    'as' => 'Business::',
    'domain' => 'cms.icheck.com.vn',
], function () {
    Route::get('/successfullyRegistered', ['as' => 'successfullyRegistered', 'uses' => '\\App\\Http\\Controllers\\Auth\\AuthController@successfullyRegistered']);

    Route::group([
        'middleware' => 'auth',
    ], function () {
        Route::group([
            'middleware' => 'business_activated',
        ], function () {
            Route::get('/password', ['as' => 'password_change_form', 'uses' => 'DashboardController@password_change_form']);
            Route::post('/password', ['as' => 'password_change', 'uses' => 'DashboardController@password_change']);
            Route::get('/download', ['as' => 'downloadForm', 'uses' => 'DashboardController@downloadForm']);
            Route::get('/download-pp', ['as' => 'downloadPP', 'uses' => 'DashboardController@downloadPP']);
            Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
            Route::match(['put', 'post'], 'upload-image', ['as' => 'image', 'uses' => 'DashboardController@image']);
            Route::get('/getChartData', ['as' => 'getChartData', 'uses' => 'DashboardController@getChartData']);
            Route::get('/{gtin}/get-report-product', ['as' => 'getReportProduct', 'uses' => 'DashboardController@getReportProduct']);


            Route::get('/huong-dan-su-dung', ['as' => 'hdsd', 'uses' => 'DashboardController@hdsd']);
            Route::group([
                'namespace' => 'Analytics',
                'prefix' => 'analytics',
                'as' => 'analytics@',
            ], function () {
                Route::group([
                    'prefix' => 'realtime',
                    'as' => 'realtime@',
                ], function () {
                    Route::get('/overview', ['as' => 'overview', 'uses' => 'RealtimeController@overview']);
                });
                Route::get('/action', ['as' => 'action', 'uses' => 'ActionController@index']);
                Route::get('/scan', ['as' => 'scan', 'uses' => 'ScanController@index']);
                Route::get('/comment', ['as' => 'comment', 'uses' => 'CommentController@index']);
                Route::get('/vote', ['as' => 'vote', 'uses' => 'VoteController@index']);
                Route::get('/like', ['as' => 'like', 'uses' => 'LikeController@index']);
                Route::get('/unlike', ['as' => 'unlike', 'uses' => 'UnlikeController@index']);
                Route::get('/geo', ['as' => 'geo', 'uses' => 'GeoController@index']);
            });

            Route::group([
                'prefix' => 'relateProductDN',
                'as' => 'relateProductDN@',
            ], function () {
                Route::get('{gtin}/sx', ['as' => 'listSx', 'uses' => 'RelateProductController@listSx']);
                Route::post('/remove-sx-product', ['as' => 'removeSx', 'uses' => 'RelateProductController@removeSx']);
                Route::post('/add-sx-product', ['as' => 'addSx', 'uses' => 'RelateProductController@addSx']);

                Route::get('{gtin}/pp', ['as' => 'listPp', 'uses' => 'RelateProductController@listPp']);
                Route::post('/remove-pp-product', ['as' => 'removePp', 'uses' => 'RelateProductController@removePP']);
                Route::post('/add-pp-product', ['as' => 'addPp', 'uses' => 'RelateProductController@addPp']);
                Route::post('/update-pp-list', ['as' => 'updatePp', 'uses' => 'RelateProductController@updatePp']);



                Route::get('{gtin}/list-relate-product-sx', ['as' => 'listRelateProduct', 'uses' => 'RelateProductController@listRelateProduct']);
                Route::post('/update-relate-product', ['as' => 'updateRelateProduct', 'uses' => 'RelateProductController@updateRelateProduct']);



                Route::get('{gtin}/list-relate-product-pp', ['as' => 'listRelateProductPp', 'uses' => 'RelateProductController@listRelateProductPp']);




            });


            Route::group([
                'prefix' => 'statistical',
                'as' => 'statistical@',
            ], function () {
                Route::get('/products', ['as' => 'products', 'uses' => 'StatisticalController@products']);
                Route::get('/categories', ['as' => 'categories', 'uses' => 'StatisticalController@categories']);
                Route::get('/locations', ['as' => 'locations', 'uses' => 'StatisticalController@locations']);
                Route::get('/ages', ['as' => 'ages', 'uses' => 'StatisticalController@ages']);

                Route::get('/getChartData', ['as' => 'getChartData', 'uses' => 'StatisticalController@getChartData']);
                Route::get('/getLocationData', ['as' => 'getLocationData', 'uses' => 'StatisticalController@getLocationData']);

            });


            Route::group([
                'prefix' => 'products',
                'as' => 'product@',
            ], function () {
                Route::post('/register-product-distributor', ['as' => 'PostRegisterProduct', 'uses' => 'ProductController@postRegisterProduct']);
                Route::get('/list-product-distributor', ['as' => 'listProductDistributor', 'uses' => 'ProductController@listProductDistributor']);
                Route::get('/register-distributor', ['as' => 'registerProduct', 'uses' => 'ProductController@registerDistributor']);
                Route::get('/cancel-distributor', ['as' => 'cancelProduct', 'uses' => 'ProductController@cancelProduct']);
                Route::get('/edit-product-distributor', ['as' => 'editProduct', 'uses' => 'ProductController@editProduct']);
                Route::post('/update-product-distributor/{id}', ['as' => 'updateProduct', 'uses' => 'ProductController@updateProduct']);

                Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'ProductController@add']);
                Route::post('/import', ['as' => 'import', 'uses' => 'ProductController@import']);
                Route::post('/importDistributor', ['as' => 'importDistributor', 'uses' => 'ProductController@importDistributor']);
                Route::post('/add', ['as' => 'store', 'uses' => 'ProductController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ProductController@edit']);
                Route::get('/{id}/analytics', ['as' => 'analytics', 'uses' => 'ProductController@analytics']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'ProductController@update']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'ProductController@delete']);
                Route::get('/{gtin}/comments', ['as' => 'comments', 'uses' => 'ProductController@comments']);


                // auto generate barcode
                Route::post('/ajaxAutoGenerate', ['as' => 'ajaxAutoGenerate', 'uses' => 'ProductController@ajaxAutoGenerate']);
                Route::post('/ajaxGetCheckCode', ['as' => 'ajaxGetCheckCode', 'uses' => 'ProductController@ajaxGetCheckCode']);

                Route::post('/answer-comment', ['as' => 'answerComment', 'uses' => 'ProductController@answerComment']);
                Route::post('/add-new-comment', ['as' => 'addComment', 'uses' => 'ProductController@addComment']);
                Route::get('/{id}/delete-comments', ['as' => 'deleteComment', 'uses' => 'ProductController@deleteComment']);
                Route::get('/{id}/pin-comments', ['as' => 'pinComment', 'uses' => 'ProductController@pinComment']);
                Route::get('/{id}/unpin-comments', ['as' => 'unpinComment', 'uses' => 'ProductController@unpinComment']);
            });

            Route::group([
                'prefix' => 'gln',
                'as' => 'gln@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'GLNController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'GLNController@add']);
                Route::post('/add', ['as' => 'store', 'uses' => 'GLNController@store']);
                Route::get('/suggestInfo/{gln}', ['as' => 'suggestInfo', 'uses' => 'GLNController@suggestInfo']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'GLNController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'GLNController@update']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'GLNController@delete']);
            });
            Route::group([
                'prefix' => 'notifications',
                'as' => 'notification@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'NotificationController@index']);
                Route::get('/list', ['as' => 'list', 'uses' => 'NotificationController@listNotification']);
                Route::get('read/{id}', ['as' => 'read', 'uses' => 'NotificationController@read']);
            });

            Route::group([
                'prefix' => 'questions',
                'as' => 'question@'
            ],function(){
                Route::get('/', ['as' => 'index', 'uses' => 'QuestionController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'QuestionController@add']);
                Route::post('/add', ['as' => 'store', 'uses' => 'QuestionController@store']);
//                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'QuestionController@edit']);
//                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'QuestionController@update']);
                Route::post('/get-answer-question',['as' => 'getAnswerQuestion','uses' => 'QuestionController@getAnswerQuestion']);
                Route::post('/add-answer-question',['as' => 'addAnswerQuestion','uses' => 'QuestionController@addAnswerQuestion']);
                Route::get('/get-file/{id}',['as' => 'getFile','uses' => 'QuestionController@getFile']);
                Route::post('/change-status-question',['as' => 'changeStatus','uses' => 'QuestionController@changeStatus']);

            });

            Route::group([
                'prefix' => 'messages',
                'as' => 'message@'
            ],function(){
                Route::get('/', ['as' => 'index', 'uses' => 'MessageController@index']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'MessageController@delete']);
                Route::get('/add', ['as' => 'add', 'uses' => 'MessageController@add']);
                Route::post('/add', ['as' => 'store', 'uses' => 'MessageController@store']);
            });
            Route::group([
               'prefix' => 'chat',
                'as' => 'chat@'
            ],function(){
                Route::get('/', ['as' => 'index', 'uses' => 'ChatController@index']);
                Route::get('/search', ['as' => 'search', 'uses' => 'ChatController@search']);
                Route::get('searchGtin', ['as' => 'searchGtin', 'uses' => 'ChatController@searchGtin']);
                Route::post('send-notification', ['as' => 'sendNotification', 'uses' => 'ChatController@sendNotification']);
            });
            Route::group([
                'prefix' => 'highlights',
                'as' => 'highlight@'
            ],function(){
                Route::get('/', ['as' => 'index', 'uses' => 'HighLightController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'HighLightController@add']);
                Route::post('/add', ['as' => 'store', 'uses' => 'HighLightController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'HighLightController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'HighLightController@update']);

                Route::get('/list-atrr',['as' => 'search','uses' => 'HighLightController@search']);
            });
            Route::group([
                'prefix' => 'notification-user',
                'as' => 'notificationUser@'
            ],function(){
                Route::get('/', ['as' => 'index', 'uses' => 'NotificationUserController@index']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'NotificationUserController@delete']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'NotificationUserController@edit']);
                Route::get('/add', ['as' => 'add', 'uses' => 'NotificationUserController@add']);
                Route::post('/add', ['as' => 'store', 'uses' => 'NotificationUserController@store']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'NotificationUserController@update']);
            });
        });
    });
});




//Staff CMS
Route::group([
    'namespace' => 'Staff',
    'prefix' => 'staff@kimochi',
    'as' => 'Staff::',
    'domain' => 'cms.icheck.com.vn',
], function () {
    // Authentication Routes...
    Route::get('login', [
        'as' => 'getLogin',
        'uses' => 'Auth\AuthController@showLoginForm'
    ]);
    Route::post('login', [
        'as' => 'postLogin',
        'uses' => 'Auth\AuthController@login'
    ]);
    Route::get('logout', [
        'as' => 'getLogout',
        'uses' => 'Auth\AuthController@logout'
    ]);

    // Registration Routes...
    Route::get('register', 'Auth\AuthController@showRegistrationForm');
    Route::post('register', 'Auth\AuthController@register');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    Route::group([
        'middleware' => 'auth:staff',
    ], function () {
        Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
        Route::get('log', ['as' => 'log', 'uses' => 'LogController@index']);
        Route::get('log-search-vendor', ['as' => 'logSearchVendor', 'uses' =>  'LogController@logSearchVendor']);


        Route::get('/view-job', ['as' => 'viewJob', 'uses' => 'DashboardController@viewJob']);
        Route::get('/retry-job/{id}', ['as' => 'retryJob', 'uses' => 'DashboardController@retryJob']);
        Route::get('/delete-job/{id}', ['as' => 'deleteJob', 'uses' => 'DashboardController@deleteJob']);
        Route::post('/getTokenFireBase', ['as' => 'getTokenFireBase', 'uses' => 'DashboardController@getTokenFireBase']);
        Route::get('/password', ['as' => 'password_change_form', 'uses' => 'PasswordController@index']);
        Route::post('/password', ['as' => 'password_change', 'uses' => 'PasswordController@change']);
        Route::group([
            'prefix' => 'notifications',
            'as' => 'notification@',
        ], function () {

            Route::get('/', ['as' => 'index', 'uses' => 'NotificationController@index']);
            Route::get('/read-all', ['as' => 'markRead', 'uses' => 'NotificationController@readAll']);
            Route::get('/{id}', ['as' => 'read', 'uses' => 'NotificationController@read']);


        });


        Route::group([
            'prefix' => 'managerUser',
            'as' => 'managerUser@',
        ], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ManagerUserController@index']);
            Route::post('/import/{icheck_id}', ['as' => 'import', 'uses' => 'ManagerUserController@import']);
            Route::get('/block/{id}', ['as' => 'block', 'uses' => 'ManagerUserController@block']);
            Route::get('/verify/{id}', ['as' => 'verify', 'uses' => 'ManagerUserController@verify']);

        });


        Route::group([
            'prefix' => 'reportDashboard',
            'as' => 'reportDashboard@',
        ], function () {
            Route::get('/report-product', ['as' => 'product', 'uses' => 'ReportDashboardController@product']);
            Route::get('/report-category', ['as' => 'category', 'uses' => 'ReportDashboardController@category']);
            Route::get('/report-vendor', ['as' => 'vendor', 'uses' => 'ReportDashboardController@vendor']);

            Route::get('/{id}/report-category-detail', ['as' => 'categoryDetail', 'uses' => 'ReportDashboardController@categoryDetail']);
            Route::get('/{id}/report-vendor-detail', ['as' => 'vendorDetail', 'uses' => 'ReportDashboardController@vendorDetail']);

        });

        Route::group([
            'namespace' => 'Analytics',
            'prefix' => 'analytics',
            'as' => 'analytics@',
        ], function () {
            Route::get('/scan', ['as' => 'scan', 'uses' => 'ScanController@index']);
            Route::get('/comment', ['as' => 'comment', 'uses' => 'CommentController@index']);
            Route::get('/vote', ['as' => 'vote', 'uses' => 'VoteController@index']);
            Route::get('/like', ['as' => 'like', 'uses' => 'LikeController@index']);
            Route::get('/unlike', ['as' => 'unlike', 'uses' => 'UnlikeController@index']);

            // tool thong ke db moi:
            Route::get('/post-comment', ['as' => 'post_comment', 'uses' => 'PostCommentController@index']);
            Route::get('/product-comment', ['as' => 'product_comment', 'uses' => 'ProductCommentController@index']);
            Route::get('/ga', ['as' => 'ga', 'uses' => 'GAController@index']);

        });

        Route::group([
            'prefix' => 'operation',
            'as' => 'operation@',
        ], function () {
            Route::get('/index', ['as' => 'index', 'uses' => 'OperationController@index']);
            Route::any('/add', ['as' => 'add', 'uses' => 'OperationController@add']);
            Route::get('/time', ['as' => 'time', 'uses' => 'OperationController@time']);
            Route::any('/create', ['as' => 'create', 'uses' => 'OperationController@create']);
            Route::get('/view', ['as' => 'view', 'uses' => 'OperationController@view']);
            Route::any('/asy', ['as' => 'asy', 'uses' => 'OperationController@asy']);
            Route::get('/product', ['as' => 'product', 'uses' => 'OperationController@product']);
            Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'OperationController@batchDelete']);
            Route::delete('/{id}', ['as' => 'delete', 'uses' => 'OperationController@delete']);
            Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'OperationController@edit']);
            Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'OperationController@update']);
            Route::any('/barcode', ['as' => 'barcode', 'uses' => 'OperationController@barcode']);
            Route::any('/notifies', ['as' => 'notifies', 'uses' => 'OperationController@notifies']);
            Route::any('/{id}/editNotifies', ['as' => 'editNotifies', 'uses' => 'OperationController@editNotifies']);
            Route::any('/{id}/deleteNotifies', ['as' => 'deleteNotifies', 'uses' => 'OperationController@deleteNotifies']);
            Route::any('/{id}/accept', ['as' => 'accept', 'uses' => 'OperationController@accept']);
            Route::any('/{id}/cancel', ['as' => 'cancel', 'uses' => 'OperationController@cancel']);
            Route::any('/cancels', ['as' => 'cancels', 'uses' => 'OperationController@cancels']);
            Route::any('/accepts', ['as' => 'accepts', 'uses' => 'OperationController@accepts']);
        });

        Route::group([
            'namespace' => 'Management',
            'prefix' => 'management',
            'as' => 'Management::',
        ], function () {
            Route::group([
                'prefix' => 'businesses',
                'as' => 'business@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'BusinessController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'BusinessController@add']);
                Route::post('/', ['as' => 'store', 'uses' => 'BusinessController@store']);
                Route::get('/map', ['uses' => 'BusinessController@getMap']);
                Route::post('/map', ['uses' => 'BusinessController@postMap']);
                Route::delete('/batch/disapprove', ['as' => 'batchDisapprove', 'uses' => 'BusinessController@batchDisapprove']);
                Route::match(['put', 'patch'], '/batch/activate', ['as' => 'batchActivate', 'uses' => 'BusinessController@batchActivate']);
                Route::match(['put', 'patch'], '/batch/deactivate', ['as' => 'batchDeactivate', 'uses' => 'BusinessController@batchDeactivate']);
                Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'BusinessController@batchDelete']);
                Route::get('/{id}', ['as' => 'show', 'uses' => 'BusinessController@show']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'BusinessController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'BusinessController@update']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'BusinessController@delete']);
                Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'BusinessController@approve']);
                Route::delete('/{id}/disapprove', ['as' => 'disapprove', 'uses' => 'BusinessController@disapprove']);
            });

            Route::group([
                'prefix' => 'logScanNotFound',
                'as' => 'logScanNotFound@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'LogScanNotFoundController@index']);

            });
            Route::group([
                'prefix' => 'user',
                'as' => 'user@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'UserController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'UserController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'UserController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'UserController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'UserController@delete']);


            });
            Route::group([
                'prefix' => 'statistical-vendor-business',
                'as' => 'statisticalVendorBusiness@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'StatisticalVendorBusinessController@index']);
                Route::get('/{gln}/product-by-vendor', ['as' => 'productByVendor', 'uses' => 'StatisticalVendorBusinessController@productByVendor']);
                Route::get('/{gtin}/comment-by-vendor', ['as' => 'commentByVendor', 'uses' => 'StatisticalVendorBusinessController@commentByVendor']);


            });
            Route::group([
                'prefix' => 'fake',
                'as' => 'fake@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'FakeUserController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'FakeUserController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'FakeUserController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'FakeUserController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'FakeUserController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'FakeUserController@delete']);
                Route::get('/{id}/block', ['as' => 'block', 'uses' => 'FakeUserController@block']);
                Route::get('/collaboratorApp', ['as' => 'collaboratorApp', 'uses' => 'FakeUserController@collaboratorApp']);
            });

            Route::group([
                'prefix' => 'virtualUser',
                'as' => 'virtualUser@',
            ], function () {
                Route::get('/{user}/posts', ['as' => 'post.list', 'uses' => 'VirtualUserPostController@listPost']);
                Route::get('/{user}/posts/create', ['as' => 'post.add', 'uses' => 'VirtualUserPostController@addPost']);
                Route::post('/{user}/posts', ['as' => 'post.store', 'uses' => 'VirtualUserPostController@create']);
                Route::get('/{user}/posts/{post}/edit', ['as' => 'post.edit', 'uses' => 'VirtualUserPostController@edit']);
                Route::put('/{user}/posts/{post}', ['as' => 'post.update', 'uses' => 'VirtualUserPostController@update']);
                Route::get('/{user}/posts/{post}', ['as' => 'post.delete', 'uses' => 'VirtualUserPostController@delete']);
                Route::get('/posts/{post}/comments', ['as' => 'post.comments.list', 'uses' => 'VirtualUserPostController@comments']);
                Route::post('/posts/{post}/comments', ['as' => 'post.comments.create', 'uses' => 'VirtualUserPostController@createComment']);
                Route::get('/comments', ['as' => 'comment.add', 'uses' => 'VirtualUserCommentController@add']);
                Route::post('/comments', ['as' => 'comment.create', 'uses' => 'VirtualUserCommentController@create']);
                Route::post('/comments/{comment}/like', ['as' => 'comment.like', 'uses' => 'VirtualUserPostController@likeComment']);
                Route::get('/posts', ['as' => 'post.all.list', 'uses' => 'VirtualUserPostController@allListPost']);
                Route::get('posts/{post}/likes', ['as' => 'post.like', 'uses' => 'VirtualUserLikeController@likePost']);
                Route::get('posts/{post}/ajax-likes', ['as' => 'post.ajax-like', 'uses' => 'VirtualUserLikeController@ajaxLikePost']);
                Route::get('/users/like-posts', ['as' => 'user-like-posts', 'uses' => 'FakeUserController@listPostLike']);
                Route::post('/users/like-posts', ['as' => 'user-like-posts', 'uses' => 'FakeUserController@likePost']);
            });

            Route::group([
                'prefix' => 'role',
                'as' => 'role@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'RoleController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'RoleController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'RoleController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'RoleController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'RoleController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'RoleController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'RoleController@search']);

            });

            Route::group([
                'prefix' => 'permission',
                'as' => 'permission@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'PermissionController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'PermissionController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'PermissionController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'PermissionController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'PermissionController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'PermissionController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'PermissionController@search']);

            });

            Route::group([
                'prefix' => 'survey',
                'as' => 'survey@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'SurveyController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'SurveyController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'SurveyController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'SurveyController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'SurveyController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'SurveyController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'SurveyController@search']);

            });

            Route::group([
                'prefix' => 'vendor',
                'as' => 'vendor@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'VendorController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'VendorController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'VendorController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'VendorController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'VendorController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'VendorController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'VendorController@search']);
                Route::put('/{id}/vendorInline', ['as' => 'vendorInline', 'uses' => 'VendorController@vendorInline']);
            });

            Route::group([
                'prefix' => 'top_hot_product',
                'as' => 'top_hot_product@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'TopHotProductController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'TopHotProductController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'TopHotProductController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'TopHotProductController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'TopHotProductController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'TopHotProductController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'TopHotProductController@search']);

            });

            Route::group([
                'prefix' => 'top_scan_product',
                'as' => 'top_scan_product@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'TopScanProductController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'TopScanProductController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'TopScanProductController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'TopScanProductController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'TopScanProductController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'TopScanProductController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'TopScanProductController@search']);

            });

            Route::group([
                'prefix' => 'category',
                'as' => 'category@',
            ], function () {

                Route::get('/', ['as' => 'index', 'uses' => 'CategoryController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'CategoryController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'CategoryController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'CategoryController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'CategoryController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'CategoryController@delete']);

                Route::get('/category-attr', ['as' => 'addAttr', 'uses' => 'CategoryController@addAttr']);
                Route::post('/category-attr', ['as' => 'addAttrPost', 'uses' => 'CategoryController@addAttrPost']);
                Route::get('/list-category-attr', ['as' => 'listAttr', 'uses' => 'CategoryController@listAttr']);

                Route::get('/{id}/edit-attr', ['as' => 'editAttr', 'uses' => 'CategoryController@editAttr']);
                Route::post('/{id}/update-attr', ['as' => 'updateAttr', 'uses' => 'CategoryController@updateAttr']);
                Route::get('/{id}/delete-attr', ['as' => 'deleteAttr', 'uses' => 'CategoryController@deleteAttr']);
            });


            Route::group([
                'namespace' => 'BusinessPermission',
                'prefix' => 'business-permission',
                'as' => 'businessPermission@',
            ], function () {

                Route::group([
                    'prefix' => 'role',
                    'as' => 'role@',
                ], function () {
                    Route::get('/', ['as' => 'index', 'uses' => 'RoleController@index']);
                    Route::get('/add', ['as' => 'add', 'uses' => 'RoleController@add']);
                    Route::post('/store', ['as' => 'store', 'uses' => 'RoleController@store']);
                    Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'RoleController@edit']);
                    Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'RoleController@update']);
                    Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'RoleController@delete']);
                    Route::post('/search', ['as' => 'search', 'uses' => 'RoleController@search']);

                });

                Route::group([
                    'prefix' => 'permission',
                    'as' => 'permission@',
                ], function () {
                    Route::get('/', ['as' => 'index', 'uses' => 'PermissionController@index']);
                    Route::get('/add', ['as' => 'add', 'uses' => 'PermissionController@add']);
                    Route::post('/store', ['as' => 'store', 'uses' => 'PermissionController@store']);
                    Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'PermissionController@edit']);
                    Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'PermissionController@update']);
                    Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'PermissionController@delete']);
                    Route::post('/search', ['as' => 'search', 'uses' => 'PermissionController@search']);

                });

            });



            Route::group([
                'prefix' => 'relate_product',
                'as' => 'relateProduct@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'RelateProductController@index']);
                Route::post('/add-product', ['as' => 'addProduct', 'uses' => 'RelateProductController@addProduct']);
                Route::post('/add-product-category', ['as' => 'addProductCategory', 'uses' => 'RelateProductController@addProductCat']);
                Route::post('/add-product-category-all', ['as' => 'addProductCategoryAll', 'uses' => 'RelateProductController@addProductCatAll']);
                Route::post('/add-product-vendor', ['as' => 'addProductVendor', 'uses' => 'RelateProductController@addProductVendor']);
                Route::post('/add-product-vendor-all', ['as' => 'addProductVendorAll', 'uses' => 'RelateProductController@addProductVendorAll']);
                Route::post('/add-category', ['as' => 'addCat', 'uses' => 'RelateProductController@addCat']);
                Route::post('/add-vendor', ['as' => 'addVendor', 'uses' => 'RelateProductController@addVendor']);
                Route::post('/order', ['as' => 'order', 'uses' => 'RelateProductController@order']);
                Route::post('/deleteRelateProduct/{id}', ['as' => 'deleteRelateProduct', 'uses' => 'RelateProductController@deleteRelateProduct']);
                Route::post('/deleteRelateAll/{id}', ['as' => 'deleteRelateAll', 'uses' => 'RelateProductController@deleteRelateAll']);

            });

            Route::group([
                'prefix' => 'post',
                'as' => 'post@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'PostController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'PostController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'PostController@store']);
                Route::match(['post'], '/{id}/approve', ['as' => 'approve', 'uses' => 'PostController@approve']);
                Route::match(['get'], '/{id}/renew', ['as' => 'renew', 'uses' => 'PostController@renew']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'PostController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'PostController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'PostController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'PostController@search']);
                //comment
                Route::get('/{id}/comments', ['as' => 'comments', 'uses' => 'PostController@comments']);
                Route::post('/answer-comment', ['as' => 'answerComment', 'uses' => 'PostController@answerComment']);
                Route::post('/add-new-comment', ['as' => 'addComment', 'uses' => 'PostController@addComment']);
                Route::get('/{id}/delete-comments', ['as' => 'deleteComment', 'uses' => 'PostController@deleteComment']);

            });
            Route::group([
                'prefix' => 'category-post',
                'as' => 'categoryPost@',
            ],function(){
                Route::get('/', ['as' => 'index', 'uses' => 'CategoryPostController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'CategoryPostController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'CategoryPostController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'CategoryPostController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'CategoryPostController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'CategoryPostController@delete']);
            });

            Route::group([
                'prefix' => 'chat',
                'as' => 'chat@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'ChatController@index']);
                Route::get('/search', ['as' => 'search', 'uses' => 'ChatController@search']);
                Route::get('searchGtin', ['as' => 'searchGtin', 'uses' => 'ChatController@searchGtin']);
                Route::post('send-notification', ['as' => 'sendNotification', 'uses' => 'ChatController@sendNotification']);

            });
            Route::group([
                'prefix' => 'questions',
                'as' => 'question@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'QuestionController@index']);
                Route::post('/get-answer-question',['as' => 'getAnswerQuestion','uses' => 'QuestionController@getAnswerQuestion']);
                Route::post('/add-answer-question',['as' => 'addAnswerQuestion','uses' => 'QuestionController@addAnswerQuestion']);

                Route::get('/get-file/{id}',['as' => 'getFile','uses' => 'QuestionController@getFile']);


            });


            Route::group([
                'prefix' => 'user-point',
                'as' => 'userPoint@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'UserPointController@index']);
                Route::get('/day/{day}', ['as' => 'detailDay', 'uses' => 'UserPointController@detailDay']);
                Route::get('/history-point/{icheck_id}', ['as' => 'historyPoint', 'uses' => 'UserPointController@historyPoint']);
                Route::get('/statistical-by-user', ['as' => 'statisticalByUser', 'uses' => 'UserPointController@statisticalByUser']);

                Route::post('update-point', ['as' => 'updatePoint', 'uses' => 'UserPointController@updatePoint']);
                Route::post('bonus-point', ['as' => 'bonusPoint', 'uses' => 'UserPointController@bonusPoint']);
            });
            Route::group([
                'prefix' => 'statistical',
                'as' => 'statistical@',
            ], function () {
                Route::get('/list-comment', ['as' => 'listComment', 'uses' => 'StatisticalController@listComment']);
                Route::get('/add-point/{id}/{icheck_id}/{point}', ['as' => 'addPoint', 'uses' => 'StatisticalController@addPoint']);
                Route::get('/not-add-point/{id}', ['as' => 'notAddPoint', 'uses' => 'StatisticalController@notAddPoint']);
                Route::get('/list-comment-by-user/{icheck_id}', ['as' => 'listCommentByUser', 'uses' => 'StatisticalController@listCommentByUser']);
            });

            Route::group([
                'prefix' => 'agency',
                'as' => 'agency@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'AgencyController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'AgencyController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'AgencyController@store']);
                Route::match(['get'], '/{id}/approve', ['as' => 'approve', 'uses' => 'AgencyController@approve']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'AgencyController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'AgencyController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'AgencyController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'AgencyController@search']);

            });

            Route::group([
                'prefix' => 'distributor',
                'as' => 'distributor@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'DistributorController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'DistributorController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'DistributorController@store']);
                Route::match(['get'], '/{id}/approve', ['as' => 'approve', 'uses' => 'DistributorController@approve']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'DistributorController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'DistributorController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'DistributorController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'DistributorController@search']);
                Route::put('/{id}/distributorInline', ['as' => 'distributorInline', 'uses' => 'DistributorController@distributorInline']);
            });

            Route::group([
                'prefix' => 'message',
                'as' => 'message@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'MessageController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'MessageController@add']);
                Route::post('/store', ['as' => 'store', 'uses' => 'MessageController@store']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'MessageController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'MessageController@update']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'MessageController@delete']);
                Route::post('/search', ['as' => 'search', 'uses' => 'MessageController@search']);

            });
            Route::group([
                'prefix' => 'notification-user',
                'as' => 'notificationUser@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'NotificationUserController@index']);
                Route::get('/{id}/approve', ['as' => 'approve', 'uses' => 'NotificationUserController@approve']);
                Route::post('/{id}/disapprove', ['as' => 'disapprove', 'uses' => 'NotificationUserController@disapprove']);

            });


            Route::group([
                'prefix' => 'gln',
                'as' => 'gln@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'GLNController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'GLNController@add']);
                Route::post('/', ['as' => 'store', 'uses' => 'GLNController@store']);
                Route::get('/viewCert/{id}', ['as' => 'viewCert', 'uses' => 'GLNController@viewCertificateFile']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'GLNController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'GLNController@update']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'GLNController@delete']);
                Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'GLNController@approve']);
            });



            Route::group([
                'prefix' => 'products',
                'as' => 'product@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'ProductController@add']);
                Route::post('/', ['as' => 'store', 'uses' => 'ProductController@store']);
                Route::post('/{id}/inline', ['as' => 'inline', 'uses' => 'ProductController@inline']);

                Route::get('{id}/product-by-business', ['as' => 'productByBusiness', 'uses' => 'ProductController@productByBusiness']);
                Route::post('/{id}/add-quota-product', ['as' => 'addQuotaProduct', 'uses' => 'ProductController@addQuotaProduct']);
                Route::post('/{id}/remove-quota-product', ['as' => 'removeQuotaProduct', 'uses' => 'ProductController@removeQuotaProduct']);
                Route::match(['put', 'patch', 'post'], '/approve', ['as' => 'approve', 'uses' => 'ProductController@approve']);
                Route::match(['put', 'patch', 'post'], '/disapprove-all', ['as' => 'disapproveAll', 'uses' => 'ProductController@disapproveAll']);

                Route::delete('/batch/disapprove', ['as' => 'batchDisapprove', 'uses' => 'ProductController@batchDisapprove']);
                Route::match(['put', 'patch'], '/batch/activate', ['as' => 'batchActivate', 'uses' => 'ProductController@batchActivate']);
                Route::match(['put', 'patch'], '/batch/deactivate', ['as' => 'batchDeactivate', 'uses' => 'ProductController@batchDeactivate']);
                Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'ProductController@batchDelete']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ProductController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'ProductController@update']);
                Route::match(['put', 'patch'], '/{id}/disapprove', ['as' => 'disapprove', 'uses' => 'ProductController@disapprove']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'ProductController@delete']);
            });

            Route::group([
                'prefix' => 'products2',
                'as' => 'product2@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'Product2Controller@index2']);
//                Route::get('/2', ['as' => 'index2', 'uses' => 'Product2Controller@index2']);
                Route::get('/report-hcm', ['as' => 'reportHCM', 'uses' => 'Product2Controller@reportHCM']);
                Route::get('/w', ['as' => 'listWarning', 'uses' => 'Product2Controller@listProductWarning']);
                Route::post('/active-warning', ['as' => 'activeWarning', 'uses' => 'Product2Controller@activeWarning']);

                Route::get('/ad', ['as' => 'ad', 'uses' => 'Product2Controller@adForm']);
                Route::post('/ad', ['uses' => 'Product2Controller@ad']);
                Route::get('/removeA', ['as' => 'removeA', 'uses' => 'Product2Controller@removeAForm']);
                Route::post('/removeA', ['uses' => 'Product2Controller@removeA']);
                Route::get('/removeD', ['as' => 'removeD', 'uses' => 'Product2Controller@removeDForm']);
                Route::post('/removeD', ['uses' => 'Product2Controller@removeD']);

                Route::get('/removeField', ['as' => 'removeField', 'uses' => 'Product2Controller@removeFieldForm']);
                Route::post('/removeField', ['uses' => 'Product2Controller@removeField']);

                Route::get('/u', ['as' => 'listByUser', 'uses' => 'Product2Controller@listProductByUser']);
                Route::get('/u/ignore/{gtin}', ['as' => 'ignoreByUser', 'uses' => 'Product2Controller@ignoreByUser']);
                Route::get('/u/approve/{gtin}', ['as' => 'approveByUser', 'uses' => 'Product2Controller@approveByUser']);
                Route::get('/u/remove/{gtin}', ['as' => 'removeByUser', 'uses' => 'Product2Controller@removeByUser']);

                Route::post('/u/approveList', ['as' => 'approveListByUser', 'uses' => 'Product2Controller@approveListByUser']);
                Route::post('/u/ignoreList', ['as' => 'ignoreListByUser', 'uses' => 'Product2Controller@ignoreListByUser']);

                Route::get('/u/moonCake/{id}', ['as' => 'moonCake', 'uses' => 'Product2Controller@moonCake']);
                Route::get('/a/{id}', ['as' => 'listByAgency', 'uses' => 'Product2Controller@listProductByAgency']);
                Route::get('/d/{id}', ['as' => 'listByDistributor', 'uses' => 'Product2Controller@listProductByDistributor']);
                Route::get('/d', ['as' => 'd', 'uses' => 'Product2Controller@searchProductByDistributor']);
                Route::get('/add', ['as' => 'add', 'uses' => 'Product2Controller@add']);
                Route::post('/import', ['as' => 'import', 'uses' => 'Product2Controller@import']);
                Route::get('/export', ['as' => 'export', 'uses' => 'Product2Controller@export']);
                Route::post('/', ['as' => 'store', 'uses' => 'Product2Controller@store']);
                Route::delete('/batch/disapprove', ['as' => 'batchDisapprove', 'uses' => 'Product2Controller@batchDisapprove']);
                Route::match(['put', 'patch'], '/batch/activate', ['as' => 'batchActivate', 'uses' => 'Product2Controller@batchActivate']);
                Route::match(['put', 'patch'], '/batch/deactivate', ['as' => 'batchDeactivate', 'uses' => 'Product2Controller@batchDeactivate']);
                Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'Product2Controller@batchDelete']);
                Route::get('/edit', ['as' => 'editByField', 'uses' => 'Product2Controller@editByField']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'Product2Controller@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'Product2Controller@update']);
                Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'Product2Controller@approve']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'Product2Controller@delete']);
                Route::put('/{id}/inline', ['as' => 'inline', 'uses' => 'Product2Controller@inline']);
                Route::put('/{id}/contributeInline', ['as' => 'contributeInline', 'uses' => 'Product2Controller@contributeInline']);
                Route::post('/delete/{gtin}', ['as' => 'delete', 'uses' => 'Product2Controller@delete']);


            });

            Route::group([
                'namespace' => 'ProductReview',
                'prefix' => 'productReviews',
                'as' => 'productReview@',
            ], function () {
                Route::group([
                    'prefix' => 'groups',
                    'as' => 'group@',
                ], function () {
                    Route::get('/', ['as' => 'index', 'uses' => 'GroupController@index']);
                    Route::get('/add', ['as' => 'add', 'uses' => 'GroupController@add']);
                    Route::post('/', ['as' => 'store', 'uses' => 'GroupController@store']);


                    Route::match(['put', 'patch'], '/batch', ['as' => 'batchUpdate', 'uses' => 'GroupController@batchUpdate']);
                    Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'GroupController@batchDelete']);

                    Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'GroupController@edit']);
                    Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'GroupController@update']);
                    Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'GroupController@approve']);
                    Route::delete('/{id}', ['as' => 'delete', 'uses' => 'GroupController@delete']);
                });

                Route::group([
                    'prefix' => 'products',
                    'as' => 'product@',
                ], function () {

                    Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
                    Route::get('/add', ['as' => 'add', 'uses' => 'ProductController@add']);
                    Route::post('/', ['as' => 'store', 'uses' => 'ProductController@store']);


                    Route::match(['put', 'patch'], '/batch', ['as' => 'batchUpdate', 'uses' => 'ProductController@batchUpdate']);
                    Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'ProductController@batchDelete']);

                    Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ProductController@edit']);
                    Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'ProductController@update']);
                    Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'ProductController@approve']);
                    Route::delete('/{id}', ['as' => 'delete', 'uses' => 'ProductController@delete']);



                });

                Route::group([
                    'prefix' => 'facebookIds',
                    'as' => 'facebookId@',
                ], function () {
                    Route::get('/', ['as' => 'index', 'uses' => 'FacebookIdController@index']);
                    Route::get('/add', ['as' => 'add', 'uses' => 'FacebookIdController@add']);
                    Route::post('/', ['as' => 'store', 'uses' => 'FacebookIdController@store']);

                    Route::delete('/batch', ['as' => 'batchDelete', 'uses' => 'FacebookIdController@batchDelete']);

                    Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'FacebookIdController@edit']);
                    Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'FacebookIdController@update']);
                    Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'FacebookIdController@approve']);
                    Route::delete('/{id}', ['as' => 'delete', 'uses' => 'FacebookIdController@delete']);
                });

                Route::group([
                    'prefix' => 'reviews',
                    'as' => 'review@',
                ], function () {
                    Route::get('/', ['as' => 'index', 'uses' => 'ReviewController@index']);

                    Route::match(['put', 'patch'], '/batchApprove', ['as' => 'batchApprove', 'uses' => 'ReviewController@batchApprove']);
                    Route::match(['put', 'patch'], '/batchDisapprove', ['as' => 'batchDisapprove', 'uses' => 'ReviewController@batchDisapprove']);


                    Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'ReviewController@approve']);
                    Route::match(['put', 'patch'], '/{id}/disapprove', ['as' => 'disapprove', 'uses' => 'ReviewController@disapprove']);
                });
            });

            Route::group([
                'prefix' => 'contribute-product',
                'as' => 'contributeProduct@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'ContributeProductController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'ContributeProductController@add']);
                Route::post('/', ['as' => 'store', 'uses' => 'ContributeProductController@store']);
                Route::post('/change-group', ['as' => 'changeGroup', 'uses' => 'ContributeProductController@changeGroup']);
                Route::match(['put', 'patch'], '/batchApprove', ['as' => 'batchApprove', 'uses' => 'ContributeProductController@batchApprove']);
                Route::match(['put', 'patch'], '/batchDisapprove', ['as' => 'batchDisapprove', 'uses' => 'ContributeProductController@batchDisapprove']);
                Route::match(['put', 'patch'], '/batchDelete', ['as' => 'batchDelete', 'uses' => 'ContributeProductController@batchDelete']);


                Route::match(['put', 'patch'], '/{id}/approve', ['as' => 'approve', 'uses' => 'ContributeProductController@approve']);
                Route::match(['put', 'patch'], '/{id}/disapprove', ['as' => 'disapprove', 'uses' => 'ContributeProductController@disapprove']);
                Route::match(['put', 'patch'], '/{id}/delete', ['as' => 'delete', 'uses' => 'ContributeProductController@delete']);

                Route::post('{id}/add-inline-gln', ['as' => 'addInlineGln', 'uses' => 'ContributeProductController@addInlineGln']);
            });

            Route::group([
                'prefix' => 'collaborators',
                'as' => 'collaborator@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'CollaboratorController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'CollaboratorController@add']);
                Route::post('/', ['as' => 'store', 'uses' => 'CollaboratorController@store']);

                Route::get('/history', ['as' => 'history', 'uses' => 'CollaboratorController@history']);
                Route::get('/list-group', ['as' => 'listGroup', 'uses' => 'CollaboratorController@listGroup']);
                Route::get('/group/add', ['as' => 'addGroup', 'uses' => 'CollaboratorController@addGroup']);
                Route::post('/group/store', ['as' => 'storeGroup', 'uses' => 'CollaboratorController@storeGroup']);
                Route::get('/group/edit/{id}', ['as' => 'editGroup', 'uses' => 'CollaboratorController@editGroup']);
                Route::get('/group/delete/{id}', ['as' => 'deleteGroup', 'uses' => 'CollaboratorController@deleteGroup']);
                Route::match(['put', 'patch'], '/group/edit/{id}', ['as' => 'updateGroup', 'uses' => 'CollaboratorController@updateGroup']);

                Route::post('/delete-list', ['as' => 'deleteList', 'uses' => 'CollaboratorController@deleteList']);
                Route::post('/change-group', ['as' => 'changeGroup', 'uses' => 'CollaboratorController@changeGroup']);


                Route::get('/{id}', ['as' => 'show', 'uses' => 'CollaboratorController@show']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'CollaboratorController@edit']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'CollaboratorController@update']);
                Route::delete('/{id}', ['as' => 'delete', 'uses' => 'CollaboratorController@delete']);
                Route::match(['put', 'patch'], '/{id}/withdrawMoney', ['as' => 'withdrawMoney', 'uses' => 'CollaboratorController@withdrawMoney']);


            });

            Route::group([
                'prefix' => 'reports',
                'as' => 'report@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'ReportController@index']);
                Route::get('/resolve', ['as' => 'resolve', 'uses' => 'ReportController@resolve']);
                Route::get('/{id}', ['as' => 'show', 'uses' => 'ReportController@show']);
                Route::get('/pending/{id}', ['as' => 'pending', 'uses' => 'ReportController@pending']);
                Route::get('/deleteFeed/{id}', ['as' => 'deleteFeed', 'uses' => 'ReportController@deleteFeed']);
            });

            Route::group([
                'prefix' => 'settings',
                'as' => 'settings@'
            ], function () {
                Route::get('/grouptype', ['as' => 'grouptype.index', 'uses' => 'AppSettingController@indexGroupType']);
                Route::get('/grouptype/create', ['as' => 'grouptype.create', 'uses' => 'AppSettingController@createGroupType']);
                Route::post('/grouptype', ['as' => 'grouptype.store', 'uses' => 'AppSettingController@storeGroupType']);
                Route::get('/grouptype/{grouptype}/edit', ['as' => 'grouptype.edit', 'uses' => 'AppSettingController@editGroupType']);
                Route::match(['put', 'patch'], '/grouptype/{grouptype}', ['as' => 'grouptype.update', 'uses' => 'AppSettingController@updateGroupType']);
                Route::delete('/grouptype/{grouptype}', ['as' => 'grouptype.delete', 'uses' => 'AppSettingController@deleteGroupType']);

                Route::get('/survey', ['as' => 'survey.index', 'uses' => 'AppSettingController@indexSurvey']);
                Route::get('/survey/create', ['as' => 'survey.create', 'uses' => 'AppSettingController@createSurvey']);
                Route::post('/survey', ['as' => 'survey.store', 'uses' => 'AppSettingController@storeSurvey']);
                Route::get('/survey/{survey}/edit', ['as' => 'survery.edit', 'uses' => 'AppSettingController@editSurvey']);
                Route::match(['put', 'patch'], '/survey/{survey}', ['as' => 'survey.update', 'uses' => 'AppSettingController@updateSurvey']);
                Route::delete('/survey/{survey}', ['as' => 'survey.delete', 'uses' => 'AppSettingController@deleteSurvey']);
            });

            Route::group([
                'prefix' => 'business-distributor',
                'as' => 'businessDistributor@',
            ], function () {
                Route::get('/business-distributor', ['as' => 'index', 'uses' => 'BusinessDistributorController@index']);
                Route::get('/list-business-distributor', ['as' => 'listBusinessDistributor', 'uses' => 'BusinessDistributorController@listBusinessDistributor']);
                Route::get('/list-edit-product-distributor', ['as' => 'listEditProductDistributor', 'uses' => 'BusinessDistributorController@listEditProductDistributor']);
                Route::get('/add-product-distributor', ['as' => 'addProductDistributor', 'uses' => 'BusinessDistributorController@addProductDistributor']);
                Route::post('/store-product-distributor', ['as' => 'storeProductDistributor', 'uses' => 'BusinessDistributorController@storeProductDistributor']);
                Route::post('/add-list-distributor', ['as' => 'addList', 'uses' => 'BusinessDistributorController@addList']);
                Route::post('/add-quota', ['as' => 'addQuota', 'uses' => 'BusinessDistributorController@addQuota']);
                Route::post('/remove-quota', ['as' => 'removeQuota', 'uses' => 'BusinessDistributorController@removeQuota']);


                Route::post('/{id}/approve', ['as' => 'approveBusiness', 'uses' => 'BusinessDistributorController@approveBusiness']);
                Route::post('/{id}/disapprove', ['as' => 'disapproveBusiness', 'uses' => 'BusinessDistributorController@disapproveBusiness']);
                Route::post('/approve', ['as' => 'approveList', 'uses' => 'BusinessDistributorController@approveList']);
                Route::post('/disapprove', ['as' => 'disapproveList', 'uses' => 'BusinessDistributorController@disapproveList']);


                Route::post('/{id}/edit-approve', ['as' => 'approveEdit', 'uses' => 'BusinessDistributorController@approveEdit']);
                Route::post('/{id}/edit-disapprove', ['as' => 'disapproveEdit', 'uses' => 'BusinessDistributorController@disapproveEdit']);
                Route::post('/edit-approve', ['as' => 'approveListEdit', 'uses' => 'BusinessDistributorController@approveListEdit']);
                Route::post('/edit-disapprove', ['as' => 'disapproveListEdit', 'uses' => 'BusinessDistributorController@disapproveListEdit']);

                Route::post('/{id}/inline', ['as' => 'inline', 'uses' => 'BusinessDistributorController@inline']);
                Route::post('/{id}/change-edit', ['as' => 'changeEdit', 'uses' => 'BusinessDistributorController@changeEdit']);
                Route::post('/{id}/delete-distributor', ['as' => 'deleteDistributor', 'uses' => 'BusinessDistributorController@deleteDistributor']);
                Route::post('/{id}/delete', ['as' => 'delete', 'uses' => 'BusinessDistributorController@delete']);
                Route::get('/list-product-business', ['as' => 'listProductBusiness', 'uses' => 'BusinessDistributorController@listProductBusiness']);
                Route::get('{id}/get-product-business', ['as' => 'getProductBusiness', 'uses' => 'BusinessDistributorController@getProductBusiness']);
                Route::get('{id}/change-permission-edit', ['as' => 'changePermissionEdit', 'uses' => 'BusinessDistributorController@changePermissionEdit']);


            });


            Route::group([
                'prefix' => 'comments',
                'as' => 'comment@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'CommentController@index']);
                Route::post('/', ['uses' => 'CommentController@batch']);
            });

        });
        Route::group([
            'namespace' => 'Craw',
            'prefix' => 'craw',
            'as' => 'Craw::',
        ], function () {

            Route::group([
                'prefix' => 'websites',
                'as' => 'website@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'WebsiteController@index']);
                Route::get('/add', ['as' => 'add', 'uses' => 'WebsiteController@add']);
                Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'WebsiteController@edit']);
                Route::get('/{id}/delete', ['as' => 'delete', 'uses' => 'WebsiteController@delete']);
                Route::post('/add', ['as' => 'store', 'uses' => 'WebsiteController@store']);
                Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'WebsiteController@update']);
                Route::get('/{id}/crawler', ['as' => 'craw', 'uses' => 'WebsiteController@craw']);
                Route::post('checkXpath',['as' => 'checkXpath','uses' => 'WebsiteController@checkXpathPost']);

                Route::get('/{id}/product-craw', ['as' => 'productCraw', 'uses' => 'WebsiteController@productCraw']);

                Route::get('/website-in-craw', ['as' => 'websiteInCraw', 'uses' => 'WebsiteController@websiteInCraw']);
                Route::get('/get-website-in-craw', ['as' => 'getWebsiteInCraw', 'uses' => 'WebsiteController@getWebsiteInCraw']);
            });

        });




        Route::group([
            'namespace' => 'MapProduct',
            'prefix' => 'map-product',
            'as' => 'mapProduct::',
        ],function(){
            Route::group([
                'prefix' => 'products',
                'as' => 'product@',
            ], function () {
                Route::get('/', ['as' => 'index', 'uses' => 'ProductController@index']);
                Route::post('/map-list', ['as' => 'mapList', 'uses' => 'ProductController@mapList']);

                Route::post('/{id}/inline/{productId}', ['as' => 'inline', 'uses' => 'ProductController@inline']);
            });
        });
    });
});


Route::group([
    'namespace' => 'Collaborator',
    'prefix' => '@collaborator',
    'as' => 'Collaborator::',
    'domain' => 's2.business.icheck.vn',
], function () {
    // Authentication Routes...
    Route::get('login', [
        'as' => 'getLogin',
        'uses' => 'Auth\AuthController@showLoginForm'
    ]);
    Route::post('login', [
        'as' => 'postLogin',
        'uses' => 'Auth\AuthController@login'
    ]);
    Route::get('logout', [
        'as' => 'getLogout',
        'uses' => 'Auth\AuthController@logout'
    ]);

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    Route::group([
        'middleware' => 'auth:collaborator',
    ], function () {
        Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);


        Route::group([
            'prefix' => 'productReviews',
            'as' => 'productReview@',
        ], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ProductReviewController@index']);
            Route::get('/add', ['as' => 'add', 'uses' => 'ProductReviewController@add']);
            Route::get('/next', ['as' => 'next', 'uses' => 'ProductReviewController@next']);
            Route::post('/', ['as' => 'submitReview', 'uses' => 'ProductReviewController@submitReview']);
            Route::get('/{id}', ['as' => 'show', 'uses' => 'ProductReviewController@show']);
            Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'ProductReviewController@edit']);
            Route::match(['put', 'patch'], '/{id}', ['as' => 'update', 'uses' => 'ProductReviewController@update']);
            Route::delete('/{id}', ['as' => 'delete', 'uses' => 'ProductReviewController@delete']);
        });
    });
});

Route::group(['middleware' => 'auth:staff', 'namespace' => 'Event'], function () {

    // Event routes
    Route::get('/events', ['as' => 'events.list', 'uses' => 'EventController@index']);
    Route::get('/events/create', ['as' => 'events.create', 'uses' => 'EventController@create']);
    Route::post('/events/', ['as' => 'events.store', 'uses' => 'EventController@store']);
    Route::get('/events/{event}', ['as' => 'events.show', 'uses' => 'EventController@show']);
    Route::get('/events/{event}/edit', ['as' => 'events.edit', 'uses' => 'EventController@edit']);
    Route::put('/events/{event}', ['as' => 'events.update', 'uses' => 'EventController@update']);
    Route::delete('/events/{event}', ['as' => 'events.delete', 'uses' => 'EventController@delete']);

    // Event gift routes
    Route::get('/events/{event}/gifts', ['as' => 'events.gifts.list', 'uses' => 'EventController@gift']);
    Route::get('/events/{event}/gifts/create', ['as' => 'events.gifts.create', 'uses' => 'EventController@createGift']);
    Route::post('/events/{event}/gifts', ['as' => 'events.gifts.store', 'uses' => 'EventController@storeGift']);

    // Event mission routes
    Route::get('/events/{event}/missions', ['as' => 'events.missions.list', 'uses' => 'EventController@mission']);
    Route::get('/events/{event}/missions/create', ['as' => 'events.missions.add', 'uses' => 'EventController@addMission']);
    Route::post('/events/{event}/missions', ['as' => 'events.missions.store', 'uses' => 'EventController@storeMission']);
    Route::delete('/events/{event}/missions/{mission}', ['as' => 'events.missions.remove', 'uses' => 'EventController@removeMission']);

    // User receiving gift routes
    Route::get('events/{event}/userReceivingGift', ['as' => 'events.userreceivinggift.list', 'uses' => 'EventController@userReceivingGift']);
    Route::get('userReceivingGift/{userReceive}/edit', ['as' => 'events.userreceivinggift.edit', 'uses' => 'UserReceivingGiftController@edit']);
    Route::put('userReceivingGift/{userReceive}/update', ['as' => 'events.userreceivinggift.update', 'uses' => 'UserReceivingGiftController@update']);

    // Gift routes
    Route::get('/gifts/{gift}/edit', ['as' => 'gifts.edit', 'uses' => 'GiftController@edit']);
    Route::put('/gifts/{gift}', ['as' => 'gifts.update', 'uses' => 'GiftController@update']);
    Route::delete('/gifts/{gift}', ['as' => 'gifts.delete', 'uses' => 'GiftController@delete']);

    // Mission routes
    Route::get('/missions', ['as' => 'missions.list', 'uses' => 'MissionController@index']);
    Route::get('/missions/create', ['as' => 'missions.create', 'uses' => 'MissionController@create']);
    Route::post('/missions', ['as' => 'missions.store', 'uses' => 'MissionController@store']);
    Route::get('/missions/{mission}/edit', ['as' => 'missions.edit', 'uses' => 'MissionController@edit']);
    Route::put('/missions/{mission}', ['as' => 'missions.update', 'uses' => 'MissionController@update']);
    Route::delete('/missions/{mission}', ['as' => 'missions.delete', 'uses' => 'MissionController@delete']);

});

Route::group([
    'namespace' => 'AccountActive',
//    'prefix' => 'account-active',
    'as' => 'accountActive::',
    'domain' => 'xacthuc.icheck.vn',
    ],function(){
    Route::get('/', ['as' => 'register', 'uses' => 'AccountController@register']);
    Route::get('/{token}/actived', ['as' => 'actived', 'uses' => 'AccountController@actived']);
    Route::post('/', ['as' => 'postRegister', 'uses' => 'AccountController@postRegister']);
});

Route::group([
    'namespace' => 'AccountActive',
    'as' => 'accountActive::',
    'domain' => 'account.icheck.com.vn',
],function(){
    Route::get('confirm/{icheck_id}/{token}', ['as' => 'confirm', 'uses' => 'AccountController@confirm']);
    Route::get('reset-password/{icheck_id}/{token}', ['as' => 'resetPassword', 'uses' => 'AccountController@resetPassword']);
    Route::post('reset-password', ['as' => 'postResetPassword', 'uses' => 'AccountController@postResetPassword']);

});


Route::group([
    'namespace' => 'AccountActive',
    'as' => 'accountActive::',
    'domain' => 'account.icheck.com.vn',
],function(){
    Route::get('confirm/{icheck_id}/{token}', ['as' => 'confirm', 'uses' => 'AccountController@confirm']);
    Route::get('reset-password/{icheck_id}/{token}', ['as' => 'resetPassword', 'uses' => 'AccountController@resetPassword']);
    Route::post('reset-password', ['as' => 'postResetPassword', 'uses' => 'AccountController@postResetPassword']);


    Route::get('reset-password-test/{icheck_id}/{token}', ['as' => 'resetPasswordTest', 'uses' => 'AccountController@resetPasswordTest']);
    Route::post('reset-password-test', ['as' => 'postResetPasswordTest', 'uses' => 'AccountController@postResetPasswordTest']);

});


