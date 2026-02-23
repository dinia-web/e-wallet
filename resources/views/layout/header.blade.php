<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Dispensasi</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="navbar">
    <i class="fas fa-bars menu-icon" onclick="toggleSidebar()"></i>

    <div class="logo">
        <img src="{{ asset('images/o.png') }}" alt="Logo">
        <span>E-Dispensasi</span>
    </div>

    <div class="profile">
        <img id="navProfilePic"
            src="{{ Auth::user()->foto 
                ? asset('storage/foto/'.Auth::user()->foto) 
                : asset('images/default.png') }}"
            class="profile-pic">
    </div>

</div>
