# Nelson Waldorf School Grocery Cards

This is the code for the grocerycards.nelsonwaldorf.org website.

## Purpose
Save-On Foods and the Kootenay Co-op have graciously partnered with the Nelson Waldorf School (NWS) Parent Action Committee (PAC) to allow the PAC to purchase gift cards at at discount from the stores and sell them at face value, with the difference between the prices being kept by the PAC to fundraise for the school.

The website automates the order tracking for these cards: people (usually parents) sign up, give the site their credit card or debit account details (these are stored in a PCI-compliant way in Stripe), and set up either a recurring order or a one-time order. The site takes care of charging people's credit cards via stripe, doing what it can for automating the debit-card interaction, managing people's accounts, tracking who's ordered what for fulfilment purposes, and tracking how much money has been raised for - and disbursed to - each class.

## Tech
The site is built using the [Laravel](https://laravel.com) framework, with a mysql database. It uses (almost entirely) server-rendered HTML with only a little bit of Livewire and Alpine.js.

The site was originally written in Laravel 3 in 2014, upgraded to Laravel 4 partway through development, and then rebuilt almost in its entirety in 2024 in Laravel 11. The original codebase can be found in [the nwscards repo](https://github.com/ddaviesbrackett/nwscards); it requires ancient versions of PHP and Laravel and Homestead and Vagrant to run. The decision to reimplement in Laravel 11 was necessary because of the impossibility of hosting Laravel 4 on PHP 7 securely and the need to rehost. The original repo has the history for the development of the database schema & migrations and the design decisions for the functionality; if the commit history here doesn't help the commit history for the analogous file there might.

## Making Changes

To begin developing this codebase: 

1. install docker
1. clone this repository
1. `./sail composer install`
1. `./sail artisan migrate`
1. `./sail up`

These instructions work on my windows laptop in WSL2 Ubuntu, if you're using sommething else like podman or Laravel Herd (which wasn't available when this rewrite started), your startup instructions will differ. 

## Details 

TODO insert diagram(s) here describing process flows, data flows
TODO describe fortify, jetstream, stripe integrations.
TODO describe console commands and major routes.