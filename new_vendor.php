<?php
$NO_PRELOAD = $NO_REDIRECT = '1';
require_once('includes/common.php');
$LOGINBTN = '<button class="login_button hidebutton" onclick="opensigninmodal();">Login</button>';
$customer_name = $customer_email = $customer_phone = $customer_id = '';
if (isset($_SESSION['udat_DC']) && !empty($_SESSION['udat_DC'])) {
    header('location: index.php');
    exit;
    $LOGINBTN = '<a class="login_button hidebutton" href="logout.php">LOG OUT</a>';
    if ($_SESSION['udat_DC']->user_level == 2) {
        $LOGINBTN .= '<a class="login_button hidebutton" href="ctrl/v_profile.php">Dashboard</a>';
    }
    if ($_SESSION['udat_DC']->user_level == 3) {
        $LOGINBTN .= '<a class="login_button hidebutton" href="ctrl/c_profile.php">My Profile</a>';
        $dataArr = GetDataFromCOND("customers",  " and iCustomerID=$sess_user_id");
        //DFA($dataArr);
        $customer_first_name = $dataArr[0]->vFirstname;
        $customer_last_name = $dataArr[0]->vLastname;
        $customer_company_name = $dataArr[0]->vName_of_comapny;
        $position = $dataArr[0]->vPosition;
        $cemail = $dataArr[0]->vEmail;
        $phone = $dataArr[0]->vPhone;
    }

    $customer_id = $_SESSION['udat_DC']->user_id;
    $customer_name = $_SESSION['udat_DC']->user_name;
    //$customer_email = $_SESSION['udat_DC']->user_email;
    //$customer_email = GetXFromYID("select vEmail from customer where iCustID = $customer_id");

    $GetQuotesBtn = '<button class="searchitem desktop" type="button" onclick="openGetQuotemodal()" id="btn_search">Get Quotes</button>';
    $GetQuotesBtn2 = '<button class="searchitem mobile" type="button" onclick="openGetQuotemodal()"><i class="fa fa-search" id="Btn_icon" style="font-size:24px"></i></button>';
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include 'load.link.php'; ?>
    <style>
        .searchCon {
            display: flex;
            width: 100%;
        }

        div#content {
            display: grid;
            padding: 10px;
            border-radius: 20px;
            /* box-shadow: rgb(204 219 232) 3px 3px 6px 0px inset, rgb(255 255 255 / 50%) -3px -3px 6px 1px inset; */
        }

        a.list-group.list-group-item-action.border.p-2 {
            color: black;
            list-style-type: none;
            text-decoration: none;
            font-family: sans-serif;
            font-size: 14px;
            letter-spacing: 1px;
            color: dimgrey;
        }

        form.search_con {
            display: grid;
        }

        a.list-group.list-group-item-action.border.p-2 {
            background: #0e2435;
            padding: 10px;
            border-radius: 10px;
            margin: 3px;
            color: #00e7ff;
            /* width: 100%; */
            font-family: monospace;
            border-right: 5px solid #00c988;
            border-bottom: 3px solid #00c988;
        }

        .registera_button {
            display: none;
        }

        @media (max-width:952px) {
            img.user_login {
                width: 50px;
                display: none;
            }

            button.login_button {
                margin-right: 0px;
                padding: 10px;
                width: 70px;
            }

            img.logo_img {
                width: 60px;
                margin-left: 200%;
            }

            .searchCon {
                display: flex;
                width: 100%;
            }

            .Register_btn {
                display: none;
            }

            h3.head_main {
                font-size: 18px;
            }

            li.backbutton {
                padding: 10px;
            }

            .registera_button {
                display: block;
            }

            ul.header-right {
                height: 250px;
            }

        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: #f1f1f1;
        }

        #regForm {
            background-color: #ffffffc4;
            margin: 100px auto;
            font-family: Raleway;
            width: 70%;
            min-width: 300px;
        }

        h1 {
            text-align: center;
        }

        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            font-family: Raleway;
            border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        button {
            background-color: #04AA6D;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            font-family: Raleway;
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        #prevBtn {
            background-color: #bbbbbb;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }

        .two_div {
            display: flex;
            padding: 40px;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            border-radius: 20px;
            /* margin: 80px; */
            justify-content: center;
        }

        .col-12 {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        form#regForm {
            margin: auto;
        }

        video.videoa {
            border-radius: 20px;
        }

        .two_div {
            padding: 0px;
            background: white;
        }

        p.login-box-msg {
            font-size: 24px;
            letter-spacing: 1px;
            font-family: sans-serif;
        }

        p.login-box-msg {
            font-weight: 100;
        }

        input.form-control {
            font-family: sans-serif;
            letter-spacing: 1px;
        }

        label.company_add {
            font-family: sans-serif;
            font-weight: 100;
            font-size: 13px;
            color: #48c4d4;
        }

        label.three_lable {
            font-family: sans-serif;
            font-size: 14px;
            /* letter-spacing: 1px; */
        }

        label:not(.form-check-label):not(.custom-file-label) {
            font-weight: 700;
            font-weight: 200;
            color: #0c5760;
            letter-spacing: 0.5px;
        }

        button#nextBtn {
            width: 100%;
            background: #00c988;
            font-family: sans-serif;
            letter-spacing: 1px;
            border: none;
        }

        .sub_button {
            display: flex;
            /* justify-content: center; */
        }

        form#regForm {
            padding-top: 10px;
            padding-bottom: 0px;
        }

        video.videoa {
            width: 355px;
            padding: 10px;
        }

        .card.card-default {
            box-shadow: none;
            background: none;

        }

        a.btn.btn-primary {
            background: #00c988;
            font-family: sans-serif;
            letter-spacing: 0.5px;
            border: none;
            box-shadow: rgb(99 99 99 / 20%) 0px 2px 8px 0px;
        }

        a.text-center {
            font-family: sans-serif;
            color: #1d57a5;
        }



        form#regForm {
            display: grid;
        }

        input.form-control {
            margin: 0px;
        }

        .ms-options-wrap {
            width: 500px;
        }

        label {
            text-align: left;
        }

        ul {
            background: white;

            /* position: relative; */
        }

        span.select2.select2-container.select2-container--default.select2-container--focus {
            width: 400px;
        }

        .mt-2,
        .my-2 {
            margin-top: 0.5rem !important;
            margin-top: 0px !important;
            margin-left: 10px !important;
        }

        .ms-options-wrap {
            width: 500px;
            width: inherit;
        }

        .form_divs {
            width: 100%;
            /* width: 100%; */
        }

        .our_buttons {
            /* display: flex; */
            margin-bottom: 50px;
            margin-top: 60px;
        }

        button#prevBtn {
            border: none;
            background: #ff7272;
            font-family: sans-serif;
            letter-spacing: 1px;
            font-weight: 500;
        }

        #regForm {
            width: 90%;
        }

        button#submitbtn {
            background: #00c988;
            font-size: 18px;
            font-family: sans-serif;
            letter-spacing: 1px;
        }

        span.select2-selection.select2-selection--single {
            border: none;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            background: #f3f3f3;
            font-family: sans-serif;
            letter-spacing: 1px;
        }

        h3.createpass_text {
            font-family: sans-serif;
            letter-spacing: 1px;
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 30px;
        }

        p.paraclass {
            font-family: sans-serif;
            letter-spacing: 1px;
            color: #9d9d9d;
            font-weight: 700;
        }

        button#prevBtn {
            font-weight: 700;
        }

        h2#result {
            font-size: 18px;
            font-family: sans-serif;
            letter-spacing: 0.5px;
        }

        h3.createpass_text {
            color: #1b1b7e;
        }

        button#nextBtn {
            font-weight: 800;
        }

        .our_buttons {
            margin-top: 20px;
        }

        .our_buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            margin: 30px;
            margin-left: 0px;
            margin-right: 0px;
        }

        button#prevBtn {
            border: none;
            background: #dddddd;
            font-family: sans-serif;
            letter-spacing: 1px;
            font-weight: 500;
            border-radius: 5px;
            color: #838383;
            margin-right: 20px;
        }

        button#nextBtn {
            border-radius: 5px;
            background: #00c988;
        }

        h3.createpass_text {
            color: #14144c;
        }

        .input-group.mb-3 {
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
        }

        .form-control {
            font-weight: 700;
            font-size: 17px;
            color: #1b1b7e;
            background: white;
        }

        h3.createpass_text {
            font-family: sans-serif;
            letter-spacing: 1px;
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 50px;
            text-align: center;
        }

        a.text-center {
            font-family: sans-serif;
            color: #1d57a5;
            font-weight: 700;
        }

        p.paraclass {
            font-family: sans-serif;
            letter-spacing: 1px;
            color: #2a4752;
            font-weight: 700;
            filter: opacity(0.5);
        }

        h2#result {
            filter: opacity(0.5);
            color: #dfdfdf;
        }

        input.form-control {
            font-size: 17px;
            color: black;
            padding: 20px;
        }

        video.videoa {
            max-width: 400px;
            width: 100%;
        }

        h3.createpass_text {
            margin-bottom: 25px;
            font-size: 36px;
            width: 100%;
            padding: 10px;
        }

        .input-group.mb-3 {
            box-shadow: rgb(50 50 93 / 25%) 0px 2px 5px -1px, rgb(0 0 0 / 30%) 0px 1px 3px -1px;
        }

        .form-group.mb-3 {
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
        }

        .card-body {
            margin-top: 40px;
        }

        .two_div {
            align-items: center;
        }

        p.paraclass {
            font-size: 14px;
            font-weight: 700;
        }

        input.form-control {
            padding: 10px;
            color: black;
        }

        .our_buttons {
            margin: 10px;
        }

        input.form-control {
            color: #7a7878;
            font-weight: 200;
            font-family: monospace;
        }

        button#submitbtn {
            margin-top: 10px;
            border-radius: 5px;
            padding: 3px;
            border-color: #00c988;
            margin-right: 20px;
        }

        a.text-center {
            font-size: 13px;
        }

        button.login_button {
            font-family: sans-serif;
        }

        button.login_button.Register_btn {
            background: black;
            color: white;
        }

        button.login_button.Register_btn {
            background: none;
            color: white;
            box-shadow: none;
            outline: none;
            text-decoration: none;
            margin: -10px;
        }

        /* i.fa.fa-briefcase {
            background: black;
            padding: 10px;
            border-radius: 50px;
        } */

        .hide {
            /* border-radius: 30px; */
            position: absolute;
            background: white;
            color: #a8a8a8;
            padding: 8px;
            margin-left: -50px;
            margin: 30px;
            margin-top: 10px;
            margin-left: 0px;
            width: 8%;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            letter-spacing: 1px;
            display: none;
        }

        .Register_btn:hover+.hide {
            display: block;
        }

        .ms-options-wrap>.ms-options {
            background: white;
            border: none;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            border-radius: 20px;
            margin-top: 10px;
            overflow-y: hidden;
            /* Hide vertical scrollbar */
            overflow-x: hidden;
            /* Hide horizontal scrollbar */
        }

        .ms-options-wrap>.ms-options::-webkit-scrollbar {
            display: none;
        }

        .ms-search {
            width: 90%;
        }

        .ms-search>input[type="text"] {
            font-family: monospace;
            letter-spacing: 1px;
            background: black;
            color: #88f2ff;
        }

        .ms-options-wrap>.ms-options>.ms-selectall.global {
            margin: 4px 5px;
            font-family: sans-serif;
            margin-left: 15px;
            /* color: #a6a6a6; */
        }

        .ms-options-wrap>.ms-options>ul label {
            font-family: sans-serif;
            letter-spacing: 1px;
            margin-right: 40px;
        }

        .ms-options-wrap>.ms-options>ul input[type="checkbox"] {
            width: auto;
            margin-top: 7px;
            border-radius: 20px;
        }

        .ms-options-wrap>button:focus,
        .ms-options-wrap>button {
            border: none;
            background: #f1f1f1;
            padding: 10px;
            border-radius: 10px;
            box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
            font-family: sans-serif;
            letter-spacing: 1px;
            border: 2px solid #28a745;
            background: white;
            color: #8b2020;
            font-family: monospace;
            overflow: auto;

        }

        li.ms-reflow.selected {
            cursor: pointer;
        }

        .form-row {
            /* width: 50%; */
            font-family: sans-serif;
            letter-spacing: 1px;
        }

        button.btn.btn-secondary.mr-2.mt-2,
        button#modalsubmit {
            padding: 10px;
            font-family: sans-serif;
            letter-spacing: 1px;
            padding-top: 0px;
            padding-bottom: 0px;
            margin: 0px;
            height: 40px;
        }

        button.btn.btn-secondary.mr-2.mt-2 {
            background: #3a3a3a;
        }

        .ms-options-wrap,
        .ms-options-wrap * {
            box-sizing: border-box;
            padding-left: 10;
            background: none;
            color: white;
        }

        .ms-options-wrap>.ms-options {
            background: white;
            border: 2px solid black;
        }

        .ms-options-wrap>.ms-options>.ms-search input {
            width: 100%;
            padding: 4px 5px;
            border: none;
            border-bottom: 1px groove;
            outline: none;
            padding: 10px;
            color: #7dfdfe;
            font-family: monospace;
        }

        .ms-options-wrap,
        .ms-options-wrap * {
            padding-left: 0px;
            display: grid;
        }

        .ms-options-wrap,
        .ms-options-wrap * {
            padding-left: 0px;
            display: grid;
            border-radius: 20px;
        }

        .ms-options-wrap>.ms-options>ul li.selected label,
        .ms-options-wrap>.ms-options>ul label:hover {
            background-color: #000000;
            color: #51f1ff;
            font-family: monospace;
            letter-spacing: 1px;
        }

        .ms-options-wrap>.ms-options {
            margin-top: 70px;
        }

        .ms-options-wrap,
        .ms-options-wrap * {
            padding: 2px;
            text-align: center;
        }

        a.ms-selectall.global {
            text-align: left;
            color: #4f0d0d;
            letter-spacing: 1px;
        }

        li.ms-reflow.selected {
            cursor: pointer;
        }

        .ms-options-wrap>.ms-options {
            background: white;
            border: 2px solid #28a745;
        }

        p.paraclassa {
            color: #00c988;
            margin-left: 20px;
        }

        .table-bordered td,
        .table-bordered th {
            border: none;
        }

        table#statetable,
        #countytable,
        #citytable {
            border-radius: 20px;
            background: #d3d3d3;
            font-family: monospace;
            letter-spacing: 1px;
            border: none;
            margin-bottom: 0px;
        }

        form {
            width: 85%;
        }

        p.state_name {
            /* font-family: monospace; */
            letter-spacing: 0.5px;
            color: #14144c;
            font-size: 14px;
            margin-left: 10px;
            margin-top: 20px;
        }

        button#submitconfirm {
            background: #00c988;
            font-family: monospace;
            color: #ffffff;
            background-color: #0cbaba;
            background-image: linear-gradient(315deg, #0cbaba 0%, #380036 74%);
            grid-column: 2/span 3;
            margin: 0px;
            border-radius: 0px;
        }

        button.btn.btn-danger {
            background: black;
            font-family: monospace;
            border: 1px solid black;
            margin: 0px;
            border-radius: 0px;
        }

        .modal-footer {
            padding: inherit;
        }

        .modal-footer {
            padding: inherit;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        .modal-header .close,
        .modal-header .mailbox-attachment-close {
            margin: 0px;
            padding: 10px;
            color: white;
            font-size: 24px;
            background: black;
            box-shadow: none;
            filter: inherit;
        }

        .modal-header {
            padding: 0px;
        }

        .modal-header .close,
        .modal-header .mailbox-attachment-close {
            font-size: 38px;
        }
        ul#header-right {
            display: none;
        }

        button#nextBtn {
            margin-top: 0px;
        }
  
.modal-header .close, .modal-header .mailbox-attachment-close {
    padding: 1rem rem;
    margin: -1rem -1rem -1rem auto;
}
.modal-header {
    padding: 15px;
}
.modal-header .close, .modal-header .mailbox-attachment-close {
    background: white;
    color: red;
}
.modal-header .close, .modal-header .mailbox-attachment-close {
    background: white;
    color: red;
    font-size: 30px;
    border: none;
    outline: none;
}
h3.createpass_text {
    font-size: 24px;
}
img.img-fluid {
    max-height: 600px;
    height: 100%;
}
.col-md-6 {
    margin-top: 30px;
}
div#main_imga {
    margin-top: 0px;
}
.newBlockA {
    display: flex;
    justify-content: center;
    align-items: center;
    background: white;
    grid-gap: 10px;
    box-shadow: rgb(0 0 0 / 24%) 0px 3px 8px;
    width: 80%;
}
.ms-options {
    position: absolute;
    z-index: 99;
}
button#prof-btn,#cust-btn {
    font-family: sans-serif;
    grid-gap: 5px;
    padding: 10px;
    font-weight: 100;
}
button#submitbtn {
    width: 100%;
    background: #ffc300;
    color: black;
    font-weight: 100;
    letter-spacing: 1px;
}
h4#cityselectorTITLE {
    font-size: 18px;
    border-bottom: 2px solid #ffc300;
    padding-bottom: 10px;
}
.modal-footera {
    display: grid;
    width: 100%;
    grid-template-columns: 1fr 1fr;
}
button#submitconfirm {
    width: 100%;
}

        @media (max-width:952px) {
            ul#header-right{
                display:block;
            }
            span.select2.select2-container.select2-container--default.select2-container--below.select2-container--focus {
    margin-left: 0px;
}
            h4#cityselectorTITLE {
                font-size: 14px;
                padding: 10px;
            }
            video.videoa {
                display: none;
            }

            p.login-box-msg {
                font-size: 18px;
            }

            label.company_add {
                font-size: 11px;
            }

            form#regForm {
                padding: 10px;
            }



            a.btn.btn-primary {
                display: none;
            }

            .mt-2,
            .my-2 {
                margin-top: 0.5rem !important;
                margin-top: 0px !important;
                margin-left: 10px !important;
            }

            ul {
                margin: 20px;
                width: 90%;
                border-radius: 20px;
            }

            .ms-options-wrap>.ms-options {
                width: 0p;
                width: 300px;
                border-radius: 20px;
                margin-top: 20px;
                min-height: 300px;
                height: 100%;
                margin-left: -10px;
                padding-left: 0px;
            }

            h3.createpass_text {
                font-size: 18px;
            }

            p.paraclass {
                font-size: 13px;
                font-weight: inherit;
            }

            .two_div {
                box-shadow: none;
                background: none;
            }

            h2#result {
                font-size: 14px;
            }

            ul#select2-state_adr-results {
                position: relative;
            }

            button.login_button {
                font-family: sans-serif;
                font-size: 14px;
                padding: 3px;
            }

            .ms-options-wrap,
            .ms-options-wrap * {
                position: initial;
                box-sizing: border-box;
                max-height: 500px;
            }

            .ms-options-wrap,
            .ms-options-wrap * {
                padding: 10px;
            }

            .ms-options-wrap,
            .ms-options-wrap * {
                padding: 10px;
                display: grid;
            }

            .ms-options-wrap>.ms-options {
                margin-top: 25%;
                margin-left: 1%;
                /* background: white; */
            }

            .modal-header {
                padding: 0px;
            }

            .modal-body {
                padding: 0px;
            }

            p.paraclassa {
                font-size: 14px;
                /* margin-left: 0px; */
                width: 90%;
                font-weight: 100;
                color: #999999;
                letter-spacing: 0.5px;
            }

            button.btn.btn-danger {
                height: 100%;
            }

            .modal-footer {
                border: 3px solid white;
                border-radius: 20px;
            }

            .modal-header .close,
            .modal-header .mailbox-attachment-close {
                margin: 0px;
                padding: 10px;
                color: white;
                font-size: 24px;
                background: black;
                box-shadow: none;
                filter: inherit;
            }



        }
    </style>
</head>

<body>

    <?php include "header.php"; ?>
    <div id="Body" class="Body">
        <!-- Main content -->
        <section class="content mt-2">
            <div class="container-fluid">


                <div class="card card-default">
                    <div class="card-header">


                        <!-- <a href="index.php" class="btn btn-primary">Back To Home</a> -->


                        <!-- The Modal -->
                        <div class="modal" id="myModal">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h3 class="createpass_text">Confirmation</h3>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <p class="paraclassa"><strong>Below is a summary of the coverage you have selected , you will receive leads from these areas </strong></p>

                                        <form id="form2" style="flex-wrap: wrap;">
                                            <p style="text-align: center;font-weight: bold;" class="state_name">State</p>
                                            <table class="table table-bordered border-primary" id="statetable">

                                                <tbody>

                                                </tbody>
                                            </table>
                                            <p style="text-align: center;font-weight: bold;" class="state_name">County</p>
                                            <table class="table table-bordered border-primary" id="countytable">

                                                <tbody>

                                                </tbody>
                                            </table>
                                            <p style="text-align: center;font-weight: bold;" class="state_name">Cities You Do Not Cover </p>
                                            <table class="table table-bordered border-primary" id="citytable">

                                                <tbody>

                                                </tbody>
                                            </table>

                                        </form>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footera">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        <button type="button" id="submitconfirm" class="btn btn-primary">Confirm coverage selection</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="">
                        <div class="row">
                            <div class="col-12">
                                <!-- <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>Warning!</strong> This alert box could indicate a warning that might need attention.
                                </div> -->

                                <!-- main Content -->
                                <div class="two_div p-3">


                                    <div class="" id="main_imga">
                                        <!-- <video class="videoa" loop="true" autoplay="autoplay" muted>
                                            <source src="Images/Register/v-banner.mp4" type="video/mp4">
                                            <source src="v-banner.ogv" type="video/mp4">
                                            Your browser does not support HTML video.
                                        </video> -->
                                        <figure class="signup-image text-center"><img class="img-fluid" src="images/signup-image.jpg"></figure>

                                    </div>
                                    <div class="col-12 col-md-6 div_two_form">
                                        <form id="regForm" action="register.php" method="POST" onsubmit="return false;" enctype="multipart/form-data">
                                            <input type="hidden" name="cityarr[]" id="cityarr">



                                            <!-- One "tab" for each step in the form: -->
                                            <div class="tab">

                                                <h3 class="createpass_text">Create New Account</h3>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="first_name" class="form-control" oninput="this.className = 'form-control'" placeholder="First name" autocomplete="off" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-user"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="last_name" oninput="this.className = 'form-control'" class="form-control" placeholder="Last name" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-user"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="c_name" oninput="this.className = 'form-control'" class="form-control" placeholder="Company name" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-building"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="number" name="phone" oninput="this.className = 'form-control'" class="form-control" placeholder="Phone" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-phone"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p for="comment" class="paraclass">Company Address (Kindly provide actual address and not a post box address )</p>


                                                <div class="form-group mb-3">
                                                    <input type="text" name="street" id="street" placeholder="Enter your street" class="form-control">
                                                </div>
                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">

                                                            <p for="comment" class="paraclass">State</p>
                                                            <select name="state_adr" class="form-control select2" data-placeholder="Select a State" id="state_adr">
                                                                <option value="0">--select--</option>

                                                                <?php
                                                                $q = "SELECT DISTINCT(state) FROM `areas`";
                                                                $r = sql_query($q);
                                                                while ($a = sql_fetch_assoc($r)) {
                                                                    echo '<option value="' . $a['state'] . '">' . $a['state'] . '</option>';
                                                                }

                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <p for="state" class="paraclass">County </p>
                                                            <select name="county_name_adr" class="form-control select2" data-placeholder="Select a County" id="county_name_adr">


                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <div class="form-group">
                                                            <p for="state" class="paraclass">City</p>
                                                            <select name="city_adr" class="form-control select2" data-placeholder="Select a City" id="city_adr">


                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <p><input name="first_name" placeholder="First name..." oninput="this.className = ''"></p>
                                        <p><input placeholder="Last name..." oninput="this.className = ''"></p> -->
                                            </div>



                                            <div class="tab">
                                                <p class="login-box-msg">
                                                <h3 class="createpass_text">Create New Password</h3>
                                                </p>
                                                <h2 id="result"></h2>
                                                <div class="input-group mb-3">
                                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-envelope"></span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <p class="paraclass">Your new password must be diffrent from previous passwords.</p>
                                                <div class="input-group mb-3">
                                                    <input type="password" name="password1" id="password1" class="form-control" placeholder="Password" required>
                                                    <div class="input-group-append">

                                                        <div class="input-group-text">

                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirm Password" required>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <span class="fas fa-lock"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <p><input placeholder="dd" oninput="this.className = ''"></p>
                                        <p><input placeholder="mm" oninput="this.className = ''"></p>
                                        <p><input placeholder="yyyy" oninput="this.className = ''"></p> -->
                                            </div>

                                            <div class="tab">
                                                <h3 class="createpass_text">Define Your Coverage</h3>
                                                <!-- <p><input placeholder="Username..." oninput="this.className = ''"></p>
                                        <p><input placeholder="Password..." oninput="this.className = ''"></p> -->
                                                <div class="form_divs">
                                                    <div class="form-group">
                                                        <p for="state" class="paraclass">Please select the state from the dropdown. </p>
                                                        <select name="state" class="form-control select2" data-placeholder="Select a State" id="state">

                                                            <?php
                                                            $q = "SELECT DISTINCT(state) FROM `areas`";
                                                            $r = sql_query($q);
                                                            while ($a = sql_fetch_assoc($r)) {
                                                                echo '<option value="' . $a['state'] . '">' . $a['state'] . '</option>';
                                                            }

                                                            ?>

                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <p for="state" class="paraclass">Click on counties you want to include in your coverage </p>
                                                        <select name="county_name[]" id="county_name" class="form-control " multiple="multiple" data-placeholder="Select a County">
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- <div class="form-group">
                                            <label for="state">Click on cities you want to include in your coverage</label>
                                            <select name="city[]" class="form-control select2" data-placeholder="Select a City" id="city">


                                            </select>
                                        </div> -->
                                                <!-- <div class="form-group">
                                            <label style="color:red ;">Click on zips you want to exclude from your coverage </label>
                                            <select class="form-control select2" multiple="multiple" id="zips" data-placeholder="Select a Zip code that you want to exclude" name="zips[]">

                                            </select>
                                        </div> -->
                                            </div>

                                            <div style="overflow:auto;" class="sub_button col-12">
                                                <div style="float:right;" class="our_buttons">
                                                    <button type="button" id="prevBtn" class="btn btn-success" onclick="nextPrev(-1)">Previous</button>
                                                    <button type="button" id="nextBtn" class="btn btn-primary" onclick="nextPrev(1)">Next</button>
                                                    <button type="submit" id="submitbtn" class="btn btn-primary" style="visibility: hidden;">Submit</button>
                                                </div>
                                            </div>

                                            <!-- Circles which indicates the steps of the form: -->
                                            <div style="text-align:center;">
                                                <span class="step"></span>

                                                <span class="step"></span>
                                                <span class="step"></span>
                                                <br>
                                                <!-- <a href="./ctrl/vendor_login.php" class="text-center">I already have a membership? Login</a> -->
                                            </div>

                                        </form>
                                    </div>

                                </div>
                                <!-- /.form-group -->
                            </div>
                            <!-- /.col -->
                            <div class="modal" id="cityselector">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="" id="cityselectorTITLE">Modal Heading</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body" id="cityselectorBody">
                                            Modal body..
                                        </div>

                                        <!-- Modal footer -->
                                        <!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        </div> -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.card-body -->

                </div>
                <!-- /.card -->


                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="plugins/multiselect/jquery.multiselect.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
    <!-- date-range-picker -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap color picker -->
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="plugins/toastr/toastr.min.js"></script>
    <!-- Bootstrap Switch -->
    <script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script src="scripts/common.js"></script>
    <script src="scripts/md5.js"></script>
    <!-- Page script -->
    <script>
        function opensigninmodal() {
            $('#loginmodal').modal('toggle');
        }

        function openGetQuotemodal() {
            $('#customer_regsiter_modal').modal('toggle');
        }

        function openSignUpModal() {
            $('#loginmodal').modal('hide');
            $('#sign_up_modal').modal('toggle');
        }
        const mysidebar = () => {
            var displayblock = document.getElementById("header-right");
            var check = document.getElementById("check");
            if (check.checked == true) {
                displayblock.style.left = 0;
            } else {
                displayblock.style.left = "-100%";
            }
        }

        $('#langOptgroup').multiselect({
            header: true,
            columns: 4,
            placeholder: 'Select Languages',
            search: true,
            selectAll: true
        });


        $('#county_name').multiselect({
            header: true,
            columns: 1,
            placeholder: 'Select Countys',
            search: true,
            selectAll: true
        });

        const validateEmail = (email) => {
            return email.match(
                /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            );
        };

        const validate = () => {
            const $result = $('#result');
            const email = $('#email').val();
            $result.text('');

            if (validateEmail(email)) {
                $result.text(email + ' is valid 😎');
                $result.css('color', 'green');
            } else {
                $result.text(email + ' is not valid 😧');
                $result.css('color', 'red');
            }
            return false;
        }


        function Getcounties() {
            let state = $('#state_adr').val();
            $.ajax({
                url: 'api/get_countys.php',
                method: 'POST',
                data: {
                    state: state

                },
                success: function(res) {
                    console.log(res);
                    var dataObj = res;
                    $('#county_name_adr').empty();

                    for (i = 0; i < dataObj.length; i++) {
                        var x = document.getElementById("county_name_adr");
                        var option = document.createElement("option");
                        option.text = dataObj[i].county_name;
                        option.value = dataObj[i].county_name;
                        x.add(option);
                    }
                    //$('.duallistbox').bootstrapDualListbox('refresh', true);

                },
                error: function(err) {
                    console.log(err);

                }
            });

        }


        function GetCountys() {
            let state = $('#state').val();
            $.ajax({
                url: 'api/get_countys.php',
                method: 'POST',
                data: {
                    state: state

                },
                success: function(res) {
                    console.log(res);
                    var dataObj = res;
                    $('#county_name').empty();
                    for (i = 0; i < dataObj.length; i++) {
                        var x = document.getElementById("county_name");
                        var option = document.createElement("option");
                        option.text = dataObj[i].county_name;
                        option.value = dataObj[i].county_name;
                        x.add(option);
                    }
                    $('#county_name').multiselect('reload');
                },
                error: function(err) {
                    console.log(err);

                }
            });
        }

        function myFunction(elem) {
            var x = document.getElementById("password1");
            console.log(elem);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
                document.getElementById("nextBtn").style.visibility = "hidden";
                document.getElementById("submitbtn").style.visibility = "visible";


            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
                document.getElementById("nextBtn").style.visibility = "visible";
                document.getElementById("submitbtn").style.visibility = "hidden";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            //console.log(x.length);
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) {
                // ... the form gets submitted:
                // document.getElementById("regForm").submit();
                // return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            //console.log(currentTab);
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            // if (currentTab == 0) {
            //     let address = $('#address').val();
            //     console.log(address.trim());
            //     if (address.trim().length <= 0) {
            //         toastr.error('Please fill out the address Field');
            //         //console.log('I am here');
            //         valid = false;

            //     }

            // }

            if (currentTab != 2) {
                x = document.getElementsByClassName("tab");
                y = x[currentTab].getElementsByTagName("input");
                // A loop that checks every input field in the current tab:
                for (i = 0; i < y.length; i++) {
                    // If a field is empty...
                    if (y[i].value == "") {
                        // add an "invalid" class to the field:
                        y[i].className += " invalid";
                        // and set the current valid status to false
                        valid = false;
                    }
                }



            }
            if (currentTab == 1) {
                let passwd1 = $('#password1').val();
                let passwd2 = $('#password2').val();
                if (passwd1 != passwd2) {
                    alert('Both the password should match');
                    valid = false;
                }
                let email = $('#email').val();
                $.ajax({
                    url: 'api/chekmail.php',
                    method: 'POST',
                    async: false,
                    data: {
                        email: email
                    },
                    success: function(res) {
                        // console.log(res);
                        if (res == 1) {
                            alert('Email address already registered with Quote Master please choose another email');
                            valid = false;

                        }

                    }

                });


            }
            if (currentTab == 2) {
                //toastr.success('Please select the zip codes that you want to exclude.');
            }
            // If the valid status is true, mark the step as finished and valid:
            //console.log(valid);
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()
            // $('#county_name').multiselect({
            //     columns: 1,
            //     placeholder: 'Select Countys',
            //     search: true,
            //     selectAll: true
            // });
        })
        $(document).ready(function() {
            GetCountys();
            // Getcounties();
            $(document).on('click', '#modalsubmit', function() {
                $('#cityselector').modal('toggle');
                let str1 = '';
                $('#state option:selected').each(function() {
                    str1 += `<tr><td>${this.text}</td></tr>`;
                });
                $('#statetable tbody').html(str1);
                let str2 = '';
                $('#county_name option:selected').each(function() {
                    str2 += `<tr><td>${this.text}</td></tr>`;
                });
                $('#countytable tbody').html(str2);
                let str3 = '';
                $('#city option:selected').each(function() {
                    console.log(this.text);
                    str3 += `<tr><td>${this.text}</td></tr>`;
                });
                $('#citytable tbody').html(str3);
                $('#myModal').modal('toggle');


            });
            $(document).on('change', '#state_adr', function() {
                let state = $('#state_adr').val();
                $.ajax({
                    url: 'api/get_countys.php',
                    method: 'POST',
                    data: {
                        state: state

                    },
                    success: function(res) {
                        console.log(res);
                        var dataObj = res;
                        $('#county_name_adr').empty();

                        for (i = 0; i < dataObj.length; i++) {
                            var x = document.getElementById("county_name_adr");
                            var option = document.createElement("option");
                            option.text = dataObj[i].county_name;
                            option.value = dataObj[i].county_name;
                            x.add(option);
                        }
                        //$('.duallistbox').bootstrapDualListbox('refresh', true);

                    },
                    error: function(err) {
                        console.log(err);

                    }
                });
            });
            $(document).on('change', '#county_name_adr', function() {
                let county_name = $('#county_name_adr').val();
                let state = $('#state_adr').val();
                //console.log(county_name);
                $.ajax({
                    url: 'api/get_citys2.php',
                    method: 'POST',
                    data: {
                        county_name: county_name,
                        state: state

                    },
                    success: function(res) {
                        // console.log(res);
                        var dataObj = res;
                        $('#city').empty();

                        for (i = 0; i < dataObj.length; i++) {
                            var x = document.getElementById("city_adr");
                            var option = document.createElement("option");
                            option.text = dataObj[i].city;
                            option.value = dataObj[i].city;
                            x.add(option);

                        }
                        //$('.duallistbox').bootstrapDualListbox('refresh', true);

                    },
                    error: function(err) {
                        console.log(err);

                    }
                });
            });



            $('#email').on('input', validate);
            $("#regForm").submit(function() {
                var passwd1 = $('#password1').val();
                event.preventDefault();
                err = 0;
                err_arr = new Array();
                ret_val = true;
                let state = $('#state').val();
                let county = $('#county_name').val();
                let city = $('#city').val();
                if (state.length < 1) {
                    toastr.error('Please select the State');
                    $('#state').focus();

                } else if (county.length < 1) {
                    toastr.error('Please select the County');
                    $('#county_name').focus();

                } else {
                    let state = $('#state').val();
                    let county = $('#county_name').val();

                    $.ajax({
                        url: 'api/gethtml.php',
                        method: 'POST',
                        data: {
                            state: state,
                            county_name: county
                        },
                        success: function(res) {
                            console.log(res);
                            var result = res.split('~~**~~');
                            var title = result[0];
                            var body = result[1];
                            $('#cityselectorTITLE').html(title);
                            $('#cityselectorBody').html(body);
                            // $('#city').multiselect();
                            $('#city').multiselect({
                                header: true,
                                columns: 1,
                                placeholder: 'Select Citys',
                                search: true,
                                selectAll: true
                            });
                            $('#cityselector').modal('show');
                        }
                    })


                    //toastr.info('Please select the Zip codes that you want to exclude');
                    //$('#cityselector').modal('toggle');
                    //console.log($('#zips option:not(:selected)'));

                    let str1 = '<ul>';
                    $('#state option:selected').each(function() {
                        str1 += `<tr><td><li>${this.text}</li></td></tr>`;
                    });
                    str1 += '</ul>';
                    $('#statetable tbody').html(str1);
                    let str2 = '<ul>';
                    $('#county_name option:selected').each(function() {
                        str2 += `<tr><td><li>${this.text}</li></td></tr>`;
                    });
                    str2 += '</ul>';
                    $('#countytable tbody').html(str2);
                    let str3 = '<ul>';
                    $('#city option:selected').each(function() {
                        console.log(this.text);
                        str3 += `<tr><td><li>${this.text}</li></td></tr>`;
                    });
                    str3 += '</ul>'
                    $('#citytable tbody').html(str3);



                    //console.log(str);

                }

                if (err > 0) {
                    err_arr[0].focus();
                    ret_val = false;
                }




                return false;
            });



            // toastr.success('Welcome To Quote Master');

            $(document).on('click', '#submitconfirm', function() {
                //alert('Hiii');
                //$("form#regForm").submit();
                let cityval = $('#city').val();
                $('#cityarr').val(cityval);
                document.getElementById("regForm").submit();
                //$("regForm").submit();


            });


            $(document).on('change', '#state', function() {
                let state = $('#state').val();
                $.ajax({
                    url: 'api/get_countys.php',
                    method: 'POST',
                    data: {
                        state: state

                    },
                    success: function(res) {
                        console.log(res);
                        var dataObj = res;
                        $('#county_name').empty();



                        for (i = 0; i < dataObj.length; i++) {
                            var x = document.getElementById("county_name");
                            var option = document.createElement("option");
                            option.text = dataObj[i].county_name;
                            option.value = dataObj[i].county_name;
                            x.add(option);
                        }
                        $('#county_name').multiselect('reload');
                    },
                    error: function(err) {
                        console.log(err);

                    }
                });
            });



        });
    </script>
    <?php include '_loginmodal.php'; ?>
    <?php include '_signup_modal.php'; ?>

</body>

</html>