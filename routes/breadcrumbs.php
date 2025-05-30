<?php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;
// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('about', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('About', route('about'));
});

Breadcrumbs::for('gallery', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Gallery', route('gallery'));
});

Breadcrumbs::for('shop.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Shop', route('shop.index'));
});

Breadcrumbs::for('shop.checkout', function (BreadcrumbTrail $trail) {
    $trail->parent('shop.index');
    $trail->push('Checkout', route('shop.checkout'));
});

Breadcrumbs::for('shop.confirmation', function (BreadcrumbTrail $trail, $order) {
    $trail->parent('shop.index');
    $trail->push('Order Confirmation', route('shop.confirmation', $order));
});

Breadcrumbs::for('donors', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Donors', route('donors'));
});

Breadcrumbs::for('faq', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('FAQ', route('faq'));
});

Breadcrumbs::for('contact', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Contact', route('contact'));
});
