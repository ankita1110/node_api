<html>
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js">
    </script>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <style type="text/css">


        body {
            background: #eee !important;
        }

        .wrapper {
            margin-top: 80px;
            margin-bottom: 80px;
        }

        .form {
            max-width: 380px;
            padding: 15px 35px 45px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px dotted grey;
        border-width:2px;


        .heading
        {
            margin-bottom: 30px;
        }


        .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
        @include box-sizing(border-box);
        }

        .te {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }




    </style>
</head>
<?php
session_start();
if(!isset($_SESSION['name']))
{
    //$a=$_REQUEST['username'];
    header("location:login.php");
}
echo $_SESSION['name'];
$_SESSION['photo']=$_REQUEST['sample'];
echo $_SESSION['photo'];
?>


<a href="login.php">Logout</a>
<div id="err"></div>
<body>
<div class="wrapper">
<form method="post" class="form" action="" name="myform" id="myform" enctype="multipart/form-data">
    <h2 class="heading">Form</h2>
    <input type="hidden"  name="id" id="id" value="">

    <input type="text" class="form-control te" name="name" id="name" placeholder="Enter Name" value="">
    <br>
    <input type="password" class="form-control te" name="password" id="password" placeholder="Enter Password" value="">
    <br>
    <input type="text"  class="form-control te" name="city" id="city" placeholder="Enter City" value="">
    <br>
    <input type="file" name="sample" id="sample">
    <input type="button" id="dis" name="disply" value="disply">
    <input type="button" class="btn btn-lg btn-success btn-block" value="save" id="bu">
    <input type="button" class="btn btn-lg btn-primary btn-block" value="update" id="update">

</form>
</div>
<div id="t1"></div>
<!--<input type="button" value="disply" id="aa">-->
<div id="mm">
</div>

<table id="tbl" class="table table-responsive-lg" border="1">
    <thead class="thead-dark">
    <th>Name</th>
    <th>City</th>
    <th>image</th>
    <th>Delete</th>
    <th>Update</th>
    </thead>
    <tbody>

    </tbody>
</table>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        function my(){
                $.ajax({
                    type: 'GET',
                    contentType: "application/json",
                    url: "http://192.168.200.29:4000/show",

                    success: function (data) {
                        var p = "";
                        for (var d in data) {
                            //console.log(data[d]["image"]);
                            if (data) {
                               ;
                                p += "<tr>";
                                p += "<td>" + data[d]["name"] + "</td>";
                                p += "<td>" + data[d]["city"] + "</td>";
                                p += "<td><img src='http://localhost:4000/"+data[d]["image"]+"' height='70px' width='70px' alt='image'></td>";
                                p += "<td><a href='http://192.168.200.29:4000/del?id="+ data[d]["id"] + "'>Delete</a></td>";
                                p += "<td><input type=\"button\" value=\"update\" class=\"b\"  id="+data[d]['id']+"   name="+data[d]['name']+"></td>";
                                p += "</tr>";
                            }
                            var d1=data[d]["id"];
                        }
                        $("#tbl tbody").html(p);
                    }
                }).done(function(){
                    $(".b").click(function () {

                        var a=$(this).attr("name");
                        var id=$(this).attr("id");

                       // var name = $("#name").val(a);
                        $.ajax({

                            type: 'GET',
                            // contentType: "application/json",
                            url: "http://192.168.200.29:4000/sel?id="+id,
                            success: function (data) {
                                //alert(data);
                               // $('#name').val(data[0].name);
                                $('#name').val(data[0].name);
                                $('#city').val(data[0].city);
                                $('#id').val(data[0].id);
                            }
                        });
                       // alert(id);



                       // alert(name.toString());
                    });
                });
            $("#update").click(function () {
                alert();
                var id=$("#id").val();
                alert(id);
                //var a=$(this).attr("id");
                // var i=$(this).attr("value");
                //var id = $("#id").val(i);
                // alert(id);
                var name=$("#name").val();
                var city=$("#city").val();
                $.ajax({

                    type: 'POST',
                    // contentType: "application/json",
                    url: "http://192.168.200.29:4000/update/?id="+id,
                    data: {id: id,name:name,city:city},
                    success: function (data) {
                        my();

                    }
                });
            });
        }
        // $("#b").click(function () {
        //     alert();
        // });

        my();

        // $("#bu").click(function () {
        //     alert("insert");
        //     my();
        // })



        $("#bu").click(function () {
           // alert();
            var form = new FormData();
            var name = $("#name").val();
            var password = $("#password").val();
            var city = $("#city").val();
            var file = document.getElementById('sample').files[0];
           // alert(file);
            if (file) {
                form.append('name', name);
                form.append('password', password);
                form.append('city', city);
                form.append('sample', file);
               // alert(form);
            }


            $.ajax({
                type: 'POST',
                url: "http://192.168.200.29:4000/insert",
                data: form,
                cache: false,
                contentType: false, //must, tell jQuery not to process the data
                processData: false,
                // error: function({data.errors}) {
                //    // var error = errors.responseJSON;
                //     alert("dd");
                //     console.log(errors);
                // },
                success: function (data) {
                    // if(data==errors)
                    // {
                    //     alert('error');
                    // }
                  //  alert("hi");
                    if(data.errors)
                    {
                        $("#err").html('must be username is minimum 3 character');
                       // alert('must be username is minimum five character');
                    }
                    else
                    {
                        $('#err').empty();
                        my();
                    }

                }

            });
        });



    });


</script>
</html>




