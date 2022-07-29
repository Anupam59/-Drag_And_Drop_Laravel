# Drag And Drop With Laravel 8
- Name : Anupam Talukdar 
- Gmail : anupam.talukdar.ac@gmail.com
- Linkedin : https://www.linkedin.com/in/anupam-talukdar/

### step -1: Create Laravel Project
```sh
composer create-project laravel/laravel:^8.0 draganddrop
```
### step -2: Make a Table and Model
```sh
php artisan make:model order -m
```
### step -3: Make a Table add this code
```sh
Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
```

### step -4: Table Migrate
```sh
php artisan migrate
```
### step -5: Make a model  add this code
```sh
protected $fillable = [
        'title', 'order'
    ];
```
### step -6: Create a Template
- Order.blade.php

And add this code.
```sh
<!DOCTYPE html>
<html>
<head>
    <title>Create Drag and Droppable Datatables Using jQuery UI Sortable in Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- this is for drop and drog in this arrange of wish order (need) -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.css"/> 
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
</head>
<body>
    <div class="row mt-5">
        <div class="col-md-10 offset-md-1">
            <h3 class="text-center mb-4">Drag and Drop Datatables Using jQuery UI Sortable in Laravel </h3>
            <table id="table" class="table table-bordered">
              <thead>
                <tr>
                  <th width="30px">#</th>  
                  <th>Title</th>
                  <th>Created At</th>
                </tr>
              </thead>
              <tbody id="tablecontents">
              <!-- get all data from Table by Controller -->
                @foreach($posts as $post)
    	            <tr class="row1" data-id="{{ $post->id }}">
    	              <td class="pl-3"><i class="fa fa-sort"></i></td>
    	              <td>{{ $post->title }}</td>
    	              <td>{{ date('d-m-Y h:m:s',strtotime($post->created_at)) }}</td>
    	            </tr>
                @endforeach
              </tbody>                  
            </table>
            <hr>
            <h5>Drag and Drop the table rows and <button class="btn btn-success btn-sm" onclick="window.location.reload()">REFRESH</button> </h5> 
    	</div>
    </div>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
    <script type="text/javascript">
      $(function () {
        $("#table").DataTable();
// this is need to Move Ordera accordin user wish Arrangement
        $( "#tablecontents" ).sortable({
          items: "tr",
          cursor: 'move',
          opacity: 0.6,
          update: function() {
              sendOrderToServer();
          }
        });
        function sendOrderToServer() {
          var order = [];
          var token = $('meta[name="csrf-token"]').attr('content');
        //   by this function User can Update hisOrders or Move to top or under
          $('tr.row1').each(function(index,element) {
            order.push({
              id: $(this).attr('data-id'),
              position: index+1
            });
          });
// the axios Post update 

        axios.post('/Custom-sortable',{
            order: order,
            _token: token
        }).then(function (response) {
                if(response.status==200){
                    console.log(response);
                }else{
                    console.log(response);
                }
            }).catch(function (error) {
                console.log(error);
        });


        //   $.ajax({
        //     type: "POST", 
        //     dataType: "json", 
        //     url: "{{ url('Custom-sortable') }}",
        //         data: {
        //       order: order,
        //       _token: token
        //     },
        //     success: function(response) {
        //         if (response.status == 200) {
        //           console.log(response);
        //         } else {
        //           console.log(response);
        //         }
        //     }
        //   });

        }
      });
    </script>
</body>
</html>	
```
### step -7: put the below code in web.php
```sh
use App\Http\Controllers\orderController;

Route::get('Custom', [orderController::class, 'index']);
Route::post('Custom-sortable', [orderController::class, 'update']);
```
### step -8: Create a controller
```sh
php artisan make:controller orderController
```
### step -9: Add this code with orderController
```sh
<?php
namespace App\Http\Controllers;
use App\Models\order;
use Illuminate\Http\Request;
class orderController extends Controller
{
    public function index() { 
        $posts = order::orderBy('order','ASC')->get();
        return view('Order',compact('posts'));
    }
    public function update(Request $request){
        $posts = order::all();
        foreach ($posts as $post) {
            foreach ($request->order as $order) {
                if ($order['id'] == $post->id) {
                    $post->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }
}
```
### step -10: Run laravel project
```sh
php artisan serve
```

