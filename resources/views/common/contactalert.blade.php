@if (Session::has('invalidcaptcha'))
    <style>
        body {
            background-color: #e6e6e6;
            width: 100%;
            height: 100%;
        }

        #success_tic .page-body {
            max-width: 300px;
            background-color: #f3ddd5;
            margin: 10% auto;
        }

        #success_tic .page-body .head {
            text-align: center;
        }

        #success_tic .close {
            opacity: 1;
            position: absolute;
            right: 0px;
            font-size: 30px;
            padding: 3px 15px;
            margin-bottom: 10px;
        }

        #success_tic .checkmark-circle {
            width: 150px;
            height: 150px;
            position: relative;
            display: inline-block;
            vertical-align: top;
        }

        .checkmark-circle .background {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #8c563d;
            position: absolute;
        }

        #success_tic .checkmark-circle .checkmark {
            border-radius: 5px;
        }

        #success_tic .checkmark-circle .checkmark.draw:after {
            -webkit-animation-delay: 300ms;
            -moz-animation-delay: 300ms;
            animation-delay: 300ms;
            -webkit-animation-duration: 1s;
            -moz-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-timing-function: ease;
            -moz-animation-timing-function: ease;
            animation-timing-function: ease;
            -webkit-animation-name: checkmark;
            -moz-animation-name: checkmark;
            animation-name: checkmark;
            -webkit-transform: scaleX(-1) rotate(135deg);
            -moz-transform: scaleX(-1) rotate(135deg);
            -ms-transform: scaleX(-1) rotate(135deg);
            -o-transform: scaleX(-1) rotate(135deg);
            transform: scaleX(-1) rotate(135deg);
            -webkit-animation-fill-mode: forwards;
            -moz-animation-fill-mode: forwards;
            animation-fill-mode: forwards;
        }

        #success_tic .checkmark-circle .checkmark:after {
            opacity: 1;
            height: 75px;
            width: 37.5px;
            -webkit-transform-origin: left top;
            -moz-transform-origin: left top;
            -ms-transform-origin: left top;
            -o-transform-origin: left top;
            transform-origin: left top;
            border-right: 15px solid #fff;
            border-top: 15px solid #fff;
            border-radius: 2.5px !important;
            content: '';
            left: 35px;
            top: 80px;
            position: absolute;
        }

        .modal {
            position: fixed;
            top: 240px;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 1050;
            display: block;
            overflow: inherit;
            outline: 0;
        }

        .fade {
            opacity: 9999999;
            transition: opacity .15s linear;
        }

        .modal-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: var(--vz-modal-header-padding);
            border-bottom: var(--vz-modal-header-border-width) solid var(--vz-modal-header-border-color);
            border-top-left-radius: var(--vz-modal-inner-border-radius);
            border-top-right-radius: var(--vz-modal-inner-border-radius);
        }


        .btn-close {
            -webkit-box-sizing: content-box;
            box-sizing: content-box;
            width: 1em;
            height: 1em;
            padding: 0.25em 0.25em;
            color: #000;
            background: transparent url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e) center/1em auto no-repeat;
            border: 0;
            border-radius: 0.25rem;
            opacity: .5;
        }

        .modal-content {
            background-color: #f3ddd5;
        }

        @-webkit-keyframes checkmark {
            0% {
                height: 0;
                width: 0;
                opacity: 1;
            }

            20% {
                height: 0;
                width: 37.5px;
                opacity: 1;
            }

            40% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }

            100% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }
        }

        @-moz-keyframes checkmark {
            0% {
                height: 0;
                width: 0;
                opacity: 1;
            }

            20% {
                height: 0;
                width: 37.5px;
                opacity: 1;
            }

            40% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }

            100% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }
        }

        @keyframes checkmark {
            0% {
                height: 0;
                width: 0;
                opacity: 1;
            }

            20% {
                height: 0;
                width: 37.5px;
                opacity: 1;
            }

            40% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }

            100% {
                height: 75px;
                width: 37.5px;
                opacity: 1;
            }
        }

        .exclamation-mark {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            /* Adjust the margin as needed */
            font-size: 36px;
            /* Adjust the font size as needed */
            color: #f3ddd5;
            /* Change the color as needed */
        }

        .exclamation-mark span {
            font-weight: bold;
            font-size: 2em;
            animation: bounce 1s infinite;
            /* Add a bouncing animation */
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .text-color {
            color: #8c563d !important;
        }
    </style>

    <!-- Modal -->
    <div id="success_tic" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="page-body">
                    <div class="head">
                        <h4 class="text-color pb-3">Invalid captcha code!</h4>
                    </div>

                    <h1 style="text-align:center;">
                        <div class="checkmark-circle">
                            <div class="background"></div>
                            <div class="exclamation-mark">
                                <span>!</span>
                            </div>
                        </div>
                    </h1>
                </div>
            </div>
        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            window.setTimeout(function() {
                $("#success_tic").fadeTo(1000, 0).slideUp(1000, function() {
                    $(this).remove();
                });
            }, 1000);
        });
    </script>
@endif
