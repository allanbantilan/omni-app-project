<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dial Pad</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
  <style>
    @import url(https://fonts.googleapis.com/css?family=Lato:400,400italic,700);

    $lightGray: #2D2D2D;
    $blue: #285EFA;
    $green: #3DE066;
    $red: #FA4A5D;

    $pulseDuration: 0.5s;

    body {
      background-color: #2196F2;
      font-family: Lato;
      font-weight: 400;
      letter-spacing: 1px;

      -webkit-touch-callout: none;
      -webkit-user-select: none;
      -khtml-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .pad {
      width: 400px;
      height: 700px;
      background-color: #232323;
      position: absolute;
      left: 0;
      top: 0;
      right: 0;
      bottom: 0;
      margin: auto;
      box-shadow: 0 30px 35px -25px black;

      animation: showPad 1s ease forwards 1;

      .dial-pad {
        .contact {
          width: 60%;
          position: relative;
          margin-left: 20%;
          margin-top: 40px;
          opacity: 0;
          transition: opacity 0.5s ease;

          &.showContact {
            opacity: 1;
          }

          .avatar {
            background-repeat: no-repeat;
            background-size: auto 100%;
            background-position: center center;
            width: 60px;
            height: 60px;
            border-radius: 100%;
            box-shadow: 0 15px 30px -10px black;
            position: absolute;
            left: 0px;
            top: 8px;
          }

          .contact-info {
            border-radius: 8px;
            width: 85%;
            margin-left: 15%;
            background-color: $lightGray;
            height: 76px;
            overflow: hidden;

            &>div {
              width: 80%;
              margin-left: 20%;
              font-size: 12px;
              margin-top: 3px;
            }

            .contact-name {
              color: #FDFDFD;
              margin-top: 12px;
            }

            .contact-position {
              font-style: italic;
              color: #AEAEAE;
            }

            .contact-number {
              color: white;

              span {
                color: $green;
                display: inline;
              }
            }
          }

          .contact-buttons {
            position: absolute;
            right: -5px;
            top: 0px;
            width: 40px;
            height: 76px;

            button {
              border: none;
              width: 25px;
              height: 25px;
              border-radius: 100%;
              box-shadow: 0 12px 25px -5px black;
              display: block;
              position: absolute;
              right: 0px;
              background-size: 75% auto;
              background-position: center center;
              background-repeat: no-repeat;

              &:focus {
                outline: none;
              }

              &.icon-message {
                background-color: #FFC44E;
                top: 5px;
              }

              &.icon-video {
                background-color: #A529F9;
                bottom: 5px;
              }
            }
          }
        }

        .phoneString {
          width: 100%;
          height: 80px;
          background-color: $lightGray;
          margin-top: 40px;

          input {
            background-color: transparent;
            width: 60%;
            margin-left: 20%;
            height: 80px;
            border: none;
            font-size: 30px;
            color: white;
            font-weight: 700;
            letter-spacing: 2px;

            &:focus {
              outline: none;
            }
          }
        }

        .digits {
          overflow: hidden;
          width: 60%;
          margin-left: 20%;
          margin-top: 20px;

          .dig-spacer {
            width: 60px;
            margin: 10px calc(50% - 90px);
            float: left;
          }

          .dig {
            color: white;
            font-size: 30px;
            float: left;
            background-color: $lightGray;
            text-align: center;
            width: 60px;
            height: 56px;
            border-radius: 100%;
            margin: 10px 0px;
            padding-top: 4px;
            font-weight: 700;
            cursor: pointer;

            &.clicked {
              animation: pulse-gray linear $pulseDuration 1;
            }

            &:nth-child(3n-1) {
              margin: 10px calc(50% - 90px);
            }

            &.astrisk {
              padding-top: 17px;
              height: 43px;
            }

            &.pound {
              padding-top: 10px;
              height: 50px;
            }

            .sub-dig {
              font-size: 8px;
              font-weight: 300;
              position: relative;
              top: -2px;
            }

            &.addPerson,
            &.goBack {
              background-repeat: no-repeat;
              background-position: center center;
              background-size: 55% auto;
              margin-bottom: 25px;
              box-shadow: 0px 25px 30px -15px black;
            }

            &.addPerson {
              background-color: #285EFA;
              background-image: url(https://s16.postimg.org/4u2rbu85t/add_Person.png);

              &.clicked {
                animation: pulse-blue linear $pulseDuration 1;
              }
            }

            &.goBack {
              background-color: #FA4A5D;
              background-image: url(https://s4.postimg.org/x6g6auu7d/back_Arrow.png);

              &.clicked {
                animation: pulse-red linear $pulseDuration 1;
              }
            }
          }
        }
      }

      .call-pad {
        opacity: 0;
        height: 0px;
        pointer-events: none;
        background-image: url(https://s21.postimg.org/x4te7wpo7/call_Background.png);
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-position: center center;
        transition: opacity 0.3s ease;
        position: absolute;
        width: 100%;

        left: 0px;
        top: 0px;
        transition: opacity 0.3s ease;

        &.in-call {
          height: 100%;
          opacity: 1;
          pointer-events: all;
        }

        .pulsate {
          opacity: 0;
          width: 150px;
          height: 0px;
          overflow: visible;
          position: relative;
          display: block;
          margin: 0 auto 0;
          top: 120px;
          transition: opacity 0.5s ease;

          &.active-call {
            animation: pulsator 2s ease infinite;
            opacity: 1;
          }

          div {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.06);
            border-radius: 100%;
            margin: auto;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;

            &:nth-child(1) {
              width: 110px;
              height: 110px;
            }

            &:nth-child(2) {
              width: 122px;
              height: 122px;
            }

            &:nth-child(3) {
              width: 134px;
              height: 134px;
            }
          }
        }

        .ca-avatar {
          width: 100px;
          height: 100px;
          margin: 70px auto;
          margin-bottom: 30px;
          display: block;
          background-color: #111111;
          border-radius: 100%;
          box-shadow: 0px 20px 25px -10px rgba(0, 0, 0, 0.8);
          background-position: center center;
          background-size: 100% auto;
          background-repeat: no-repeat;
          transition: opacity 1s ease,
            transform 1s ease;
          opacity: 0.5;
          transform: scale(0.5, 0.5);

          &.in-call {
            transform: scale(1, 1);
            opacity: 1;
          }
        }

        .ca-name,
        .ca-number,
        .ca-status {
          width: 60%;
          margin-left: 20%;
          color: white;
          text-align: center;
          font-weight: bold;
          margin-bottom: 15px;
        }

        .ca-name {
          font-size: 18px;
        }

        .ca-number {
          font-size: 28px;
          letter-spacing: 2px;
        }

        .ca-status {
          font-size: 30px;
          margin-top: 40px;
          margin-bottom: 40px;

          &:after {
            content: attr(data-dots);
            position: absolute;
          }
        }

        .ca-buttons {
          width: 70%;
          margin-left: 15%;

          .ca-b-single {
            float: left;
            width: 60px;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 100%;
            position: relative;
            margin-bottom: 40px;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: 55% auto;

            &:nth-child(3n-1) {
              margin-left: calc(100% - 230px);
              margin-right: calc(100% - 230px);
            }

            &:after {
              content: attr(data-label);
              color: white;
              position: absolute;
              text-align: center;
              font-size: 10px;
              width: 100px;
              bottom: -20px;
              left: -18px;
              letter-spacing: 2px;

            }
          }
        }
      }

    }

    .call {
      color: white;
      font-size: 30px;
      text-align: center;
      width: 60px;
      height: 60px;
      border-radius: 100%;
      margin: 10px 0px;
      font-weight: 700;
      cursor: pointer;
      position: absolute;
      left: calc(50% - 30px);
      bottom: 25px;
      box-shadow: 0px 25px 30px -15px black;
      background-color: #3DE066;

      .call-icon {
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background-size: 60% auto;
        background-repeat: no-repeat;
        background-position: center center;
        background-image: url(https://s13.postimg.org/sqno4q8sj/call.png);
        transition: transform 0.3s ease;

        &.in-call {
          -ms-transform: rotate(134deg);
          -webkit-transform: rotate(134deg);
          transform: rotate(134deg);
        }
      }

      .call-change {
        width: 60px;
        height: 60px;
        border-radius: 100%;
        overflow: hidden;

        span {
          width: 70px;
          height: 67px;
          display: block;
          background-color: $red;
          position: relative;
          top: 70px;
          left: 70px;
          border-radius: 100%;
          transition: left 0.3s ease, top 0.3s ease;
        }

        &.in-call span {
          top: -5px;
          left: -5px;
        }
      }

      &.clicked {
        animation: pulse-green linear $pulseDuration 1 forwards;
      }
    }

    @keyframes pulse-gray {
      0% {
        box-shadow: inset 0 0 0px 30px $lightGray, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }

      10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      30% {
        box-shadow: inset 0 0 0px 10px $lightGray, inset 0 0 0px 30px white;
      }

      60% {
        box-shadow: inset 0 0 0px 0px $lightGray, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }
    }

    @keyframes pulse-blue {
      0% {
        box-shadow: inset 0 0 0px 30px $blue, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }

      10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      30% {
        box-shadow: inset 0 0 0px 10px $blue, inset 0 0 0px 30px white;
      }

      60% {
        box-shadow: inset 0 0 0px 0px $blue, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }
    }

    @keyframes pulse-green {
      0% {
        box-shadow: inset 0 0 0px 30px $green, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }

      10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      30% {
        box-shadow: inset 0 0 0px 10px $green, inset 0 0 0px 30px white;
      }

      60% {
        box-shadow: inset 0 0 0px 0px $green, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);

      }
    }

    @keyframes pulse-red {
      0% {
        box-shadow: inset 0 0 0px 30px $red, inset 0 0 0px 30px white;
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }

      10% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      30% {
        box-shadow: inset 0 0 0px 10px $red, inset 0 0 0px 30px white;
      }

      60% {
        box-shadow: inset 0 0 0px 0px $red, inset 0 0 0px 0px white;
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }
    }

    @keyframes pulsator {
      0% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }

      40% {
        -ms-transform: scale(0.8, 0.8);
        -webkit-transform: scale(0.8, 0.8);
        transform: scale(0.8, 0.8);
      }

      100% {
        -ms-transform: scale(1, 1);
        -webkit-transform: scale(1, 1);
        transform: scale(1, 1);
      }
    }

    @keyframes showPad {
      0% {
        top: 50px;
        opacity: 0;
      }

      100% {
        top: 0px;
        opacity: 1;
      }
    }

    p {
      position: fixed;
      bottom: 0px;
      left: 15px;
      color: white;
      font-family: Lato;
      font-weight: 300;
      overflow: hidden;

      a:link,
      a:visited {
        color: white;
      }

      a:hover {
        opacity: 0.5;
      }

      img {
        width: 20px;
        height: 20px;
        position: relative;
        top: 6px;
      }
    }
  </style>
</head>

<body>

  <div class="pad">
    <div class="dial-pad">
      <div class="contact">
        <div class="avatar"
          style="background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/378978/profile/profile-80_1.jpg')">
        </div>
        <div class="contact-info">
          <div class="contact-name">Matt Sich</div>
          <div class="contact-position">CodePenner</div>
          <div class="contact-number">(123) 456 - 7890</div>
        </div>
        <div class="contact-buttons">
          <button class="icon-message" aria-label="Message">
            <i class="fa fa-comment"></i>
          </button>
          <button class="icon-video" aria-label="Video Call">
            <i class="fa fa-video-camera"></i>
          </button>
        </div>
      </div>

      <div class="phoneString">
        <input type="text" disabled placeholder="Enter number...">
      </div>

      <div class="digits">
        <div class="dig number-dig" name="1">1</div>
        <div class="dig number-dig" name="2">2<div class="sub-dig">ABC</div>
        </div>
        <div class="dig number-dig" name="3">3<div class="sub-dig">DEF</div>
        </div>
        <div class="dig number-dig" name="4">4<div class="sub-dig">GHI</div>
        </div>
        <div class="dig number-dig" name="5">5<div class="sub-dig">JKL</div>
        </div>
        <div class="dig number-dig" name="6">6<div class="sub-dig">MNO</div>
        </div>
        <div class="dig number-dig" name="7">7<div class="sub-dig">PQRS</div>
        </div>
        <div class="dig number-dig" name="8">8<div class="sub-dig">TUV</div>
        </div>
        <div class="dig number-dig" name="9">9<div class="sub-dig">WXYZ</div>
        </div>
        <div class="dig number-dig astrisk" name="+">*</div>
        <div class="dig number-dig pound" name="0">0</div>
        <div class="dig number-dig pound" name="#">#</div>
      </div>

      <div class="digits">
        <button class="dig addPerson action-dig"></button>
        <div class="dig-spacer">call</div>
        <button class="dig goBack action-dig"></button>
      </div>

    </div>

    <div class="call-pad">
      <div class='pulsate'>
        <div></div>
        <div></div>
        <div></div>
      </div>
      <div class="ca-avatar"
        style="background-image: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/378978/profile/profile-80_1.jpg);">
      </div>
      <div class="ca-name">Matt Sich</div>
      <div class="ca-number">123 456 7890</div>
      <div class="ca-status" data-dots="...">Calling</div>
      <div class="ca-buttons">
        <div class="ca-b-single" data-label="Add Contact" aria-label="Add Contact">
          <i class="fa fa-user-plus"></i>
        </div>
        <div class="ca-b-single" data-label="Mute" aria-label="Mute">
          <i class="fa fa-microphone-slash"></i>
        </div>
        <div class="ca-b-single" data-label="Speaker" aria-label="Speaker">
          <i class="fa fa-volume-up"></i>
        </div>
        <div class="ca-b-single" data-label="Face to Face" aria-label="Face to Face">
          <i class="fa fa-users"></i>
        </div>
        <div class="ca-b-single" data-label="Chat" aria-label="Chat">
          <i class="fa fa-comments"></i>
        </div>
        <div class="ca-b-single" data-label="Keypad" aria-label="Keypad">
          <i class="fa fa-keypad"></i>
        </div>
      </div>
    </div>
    <div class="call action-dig">
      <div class="call-change"><span></span></div>
      <div class="call-icon"></div>
    </div>
  </div>
</body>

<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/js/twilio.min.js"></script>
<script>
  //TRY TYPING IN ONE OF THESE NUMBERS:
  //
  // 1234567890
  // 0651985833
  //


  $('.number-dig').click(function() {
    //add animation
    addAnimationToButton(this);
    //add number
    var currentValue = $('.phoneString input').val();
    var valueToAppend = $(this).attr('name');
    $('.phoneString input').val(currentValue + valueToAppend);

    checkNumber();
  });


  var timeoutTimer = true;
  var timeCounter = 0;
  var timeCounterCounting = true;

  $('.action-dig').click(function() {
    //add animation
    addAnimationToButton(this);
    if ($(this).hasClass('goBack')) {
      var currentValue = $('.phoneString input').val();
      var newValue = currentValue.substring(0, currentValue.length - 1);
      $('.phoneString input').val(newValue);
      checkNumber();
    } else if ($(this).hasClass('call')) {
      if ($('.call-pad').hasClass('in-call')) {
        //remove in call class
        $(this).removeClass('end-call')
        setTimeout(function() {
          setToInCall();
        }, 500);
        timeCounterCounting = false;
        timeCounter = 0;
        hangUpCall();
        $('.pulsate').toggleClass('active-call');
        $('.phoneString input').val('');
        checkNumber();
      } else {
        // add in the call button to class
        $(this).addClass('end-call')

        //hide dial pad initially.
        $('.dial-pad').hide();

        $('.ca-status').text('Calling');
        setTimeout(function() {
          setToInCall();
          timeoutTimer = true;
          looper();
          //showActiveCallAfterAFewSeconds
          setTimeout(function() {
            timeoutTimer = false;
            timeCounterCounting = true;
            timeCounterLoop();

            $('.pulsate').toggleClass('active-call');
            $('.ca-status').animate({
              opacity: 0,
            }, 1000, function() {
              $(this).text('00:00');
              $('.ca-status').attr('data-dots', '');

              $('.ca-status').animate({
                opacity: 1,
              }, 1000);
            });
          }, 3000);
        }, 500);
      }
    } else {

    }
  });

  var timeCounterLoop = function() {

    if (timeCounterCounting) {
      setTimeout(function() {
        var timeStringSeconds = '';
        var minutes = Math.floor(timeCounter / 60.0);
        var seconds = timeCounter % 60;
        if (minutes < 10) {
          minutes = '0' + minutes;
        }
        if (seconds < 10) {
          seconds = '0' + seconds;
        }
        $('.ca-status').text(minutes + ':' + seconds);

        timeCounter += 1;

        timeCounterLoop();
      }, 2000);
    }
  };

  var setToInCall = function() {
    $('.call-pad').toggleClass('in-call');
    $('.call-icon').toggleClass('in-call');
    $('.call-change').toggleClass('in-call');
    $('.ca-avatar').toggleClass('in-call');
  };

  var dots = 0;
  var looper = function() {
    if (timeoutTimer) {

      setTimeout(function() {
        if (dots > 3) {
          dots = 0;
        }
        var dotsString = '';
        for (var i = 0; i < dots; i++) {
          dotsString += '.';
        }
        $('.ca-status').attr('data-dots', dotsString);
        dots += 1;

        looper();
      }, 500);
    }
  };

  var hangUpCall = function() {
    timeoutTimer = false;
  };

  var addAnimationToButton = function(thisButton) {
    //add animation
    $(thisButton).removeClass('clicked');
    var _this = thisButton;
    setTimeout(function() {
      $(_this).addClass('clicked');
    }, 1);
  };

  var checkNumber = function() {
    var numberToCheck = $('.phoneString input').val();
    var contactMatt = {
      name: 'Matt Sich',
      number: '123456789',
      image: 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/378978/profile/profile-80_1.jpg',
      desc: 'CodePenner'
    };
    var contactHellogiov = {
      name: 'hellogiov',
      number: '0651985833',
      image: 'http://avatars-cdn.producthunt.com/207787/220',
      desc: 'Publicis Nurun'
    };
    if (numberToCheck.length > 0 && contactMatt.number.substring(0, numberToCheck.length) == numberToCheck) {
      //show this contact!
      showUserInfo(contactMatt);
    } else if (numberToCheck.length > 0 && contactHellogiov.number.substring(0, numberToCheck.length) ==
      numberToCheck) {
      showUserInfo(contactHellogiov);
    } else {
      hideUserInfo();
    }
  };

  var showUserInfo = function(userInfo) {
    $('.avatar').attr('style', "background-image: url(" + userInfo.image + ")");
    if (!$('.contact').hasClass('showContact')) {
      $('.contact').addClass('showContact');
    }
    $('.contact-name').text(userInfo.name);
    $('.contact-position').text(userInfo.desc);
    var matchedNumbers = $('.phoneString input').val();
    var remainingNumbers = userInfo.number.substring(matchedNumbers.length);
    $('.contact-number').html("<span>" + matchedNumbers + "</span>" + remainingNumbers);

    //update call elements
    $('.ca-avatar').attr('style', 'background-image: url(' + userInfo.image + ')');
    $('.ca-name').text(userInfo.name);
    $('.ca-number').text(userInfo.number);

  };

  var hideUserInfo = function() {
    $('.contact').removeClass('showContact');
  };
</script>

<script>
  //global variables
  var token; //twilio access token
  var identity; // agent identity
  var device; // twilio device

  //setup twilio device
  // get twilio access token
  getTwilioAccessToken();

  //setup twilio device

  async function getTwilioAccessToken() {
    console.log("twilio is on");

    try {
      // get the data from the backend / access token
      const data = await (await fetch("/phone/access-token")).json();

      console.log('got the twilio access token');

      //set token
      token = data.token;

      //set identity
      identity = data.identity;

      //initialize the device
      initializeDevice();
    } catch (error) {
      console.log(error);
    }
  }

  
  //function to initialize the twilio device
  function initializeDevice() {
    console.log('initializing twilio device!')

    //init device.
    device = new Twilio.Device(token, {
      logLevel: 1,
      codecPreferences: ['opus', 'pcmu'],
      maxCallSignalingTimeoutMs: 30000
    });

    //@todo : register Event Listeners
    registerPhoneEventListeners();

    // register the device
    device.register();

    console.log('twilio device successfully initialized!')

  }

  //function for the event listener
  async function registerPhoneEventListeners() {
    //for when the call button has been clicked
    $('.call').on('click', async function() {
      //get the phone numeber to call
      var phoneNumberToCall = $('input[type="text"]').val();

      //set the twilio parameters up.
      var params = {
        To: phoneNumberToCall,
        agent: identity,
        callerId: '+18507532654',
        Location: 'us1'
      }
      console.log(params);
      // if device exists, call the client/ customer etc.
      if (device) {
        //call the agent/customer
        const call = await device.connect({
          params
        });
      }

      if (device) {
        const call = await device.connect({
          params
        })
      }

      //register call event listener



      //register hangup button listener.



    });
  }
</script>

</html>
