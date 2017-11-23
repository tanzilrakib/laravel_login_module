<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login Module</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <!-- Styles -->
        <style>
            html, body {
                /*background-color: #fff;*/
                background-color: #439e8d;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

       /*     .title {
                font-size: 84px;
            }*/

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            .hidden{
                display: none !important;
            }

            .block{
                display: block !important;
            }
            label {
                color: #3c635b !important;
                font-weight: 900 !important;
            }

        </style>
    </head>
    <body>


        <div class="flex-center position-ref full-height">

        <div class="content">
            <div class="m-b-md">
            
                <div class="class-header" style="background-color:#3c635b;color:#fafafa">
                    <h3>Login</h3>
                </div>
                <div class="card-body" style="background-color:#fafafa;padding: 15px;border-radius: 3px;">
                <label for="rad1" style="color: #3c635b;font-weight: bolder;">via Phone</label>
                <input id="rad1" type="radio" name="methodselector" value="phone">
                <label for="rad2">via Email</label>
                <input id="rad2" type="radio" checked="checked" name="methodselector" value="email">

                <form id='form' method='post' action='{{url('login-kit')}}'>
                    <input type="hidden" name="_token" id="_token">
                    <input type="hidden" name="code" id="code" />
                </form>

                <div id="phone-container" class="hidden">
                <input value="+880" id="country_code" />
                <input placeholder="phone number" id="phone_number"/>
                <button type="button" class="btn btn-md" style='background-color:#3c635b; color: #fff' onclick="smsLogin();">Login via SMS</button>
                </div>

                <div id="email-container" class="block">
                <input placeholder="email" id="email"/>
                <button type="button" class="btn btn-md" style='background-color:#3c635b; color: #fff' onclick="emailLogin();">Login via Email</button>
                </div>
                
                <div class="py-1">
                <a class="btn btn-primary" href={{url('login/facebook')}}><i class="fa fa-facebook-square" aria-hidden="true"></i> Login via Facebook</a>
                </div>

              </div>

        </div>

       


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>

      // initialize Account Kit with CSRF protection
      AccountKit_OnInteractive = function(){
        AccountKit.init(
          {
            appId:"{{env('ACCOUNTKIT_APP_ID')}}", 
            state:"{{csrf_token()}}", 
            version:"{{env('ACCOUNT_KIT_API_VERSION')}}",
            // fbAppEventsEnabled:true,
            debug:true 
          }
        );
      };

      // login callback
      function loginCallback(response) {
        if (response.status === "PARTIALLY_AUTHENTICATED") {
          
          document.getElementById("code").value = response.code;
          document.getElementById("_token").value = response.state;

          document.getElementById("form").submit();
          // Send code to server to exchange for access token
        }
        else if (response.status === "NOT_AUTHENTICATED") {
          // handle authentication failure
          alert('You Are Not Authorized!');
        }
        else if (response.status === "BAD_PARAMS") {
          // handle bad parameters
          alert('Invalid values!');
        }
      }

      // phone form submission handler
      function smsLogin() {
        var countryCode = document.getElementById("country_code").value;
        var phoneNumber = document.getElementById("phone_number").value;
        AccountKit.login(
          'PHONE', 
          {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
          loginCallback
        );
      }


      // email form submission handler
      function emailLogin() {
        var emailAddress = document.getElementById("email").value;

        AccountKit.login(
          'EMAIL',
          {emailAddress: emailAddress},
          loginCallback
        );

      }


    $('input[type=radio]').change(function(){

        if($(this).val()==='phone'){

            $("#phone-container").addClass('block').removeClass('hidden');
            $("#email-container").addClass('hidden').removeClass('block');

        } else {

            $('#email-container').addClass('block').removeClass('hidden');
            $('#phone-container').addClass('hidden').removeClass('block');

        }
    });

    </script>

    
    <!--/container-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>

    </body>
</html>
