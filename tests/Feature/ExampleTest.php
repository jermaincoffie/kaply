<?php

it('returns a successful response', function () {
    $response = $this->get('/kapper/registreer');

    $response->assertStatus(200);
});
