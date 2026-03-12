<?php

it('returns a successful response for home', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
