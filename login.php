<html>
<head>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js">
    </script>
</head>
<body>
<form method="post" action="">
    <input type="text" name="username" id="username">
    <input type="text" name="password" id="password">
    <input type="submit" name="submit" id="bu" value="submit">
</form>
</body>
<script type="text/javascript">
    $(document).ready(function() {
        $("#bu").click(function () {
           // alert();
            var username = $("#username").val();
            var password = $("#password").val();
          //  var photo=<?php echo $_SESSION['sample'];?>;
           // var photo=$('photo')
            alert(username+""+password);
            $.ajax({

                type: 'POST',
                url: "http://192.168.200.29:4000/login",
                data: {username:username,password:password},
                success: function (data) {
                   // console.log("data " +data);
                    alert(data);
                    // $(".my").html(data);
                    if(data)
                    {
                        console.log('if');
                        //alert(data);
                       // my();
                       window.location= "session.php?username="+username;
                        //alert('success');
                    }
                }
            });
        });
    });
        </script>
</html>