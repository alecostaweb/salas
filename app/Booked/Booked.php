<?php

namespace App\Booked;

use GuzzleHttp\Client;

class Booked
{
    private $session;

    public function __construct()
    {
        $client = new Client();
        $res = $client->request('POST', config('salas.bookedHost') . '/Authentication/Authenticate', [
            'json' => [
                'username' => config('salas.bookedUser'),
                'password' => config('salas.bookedPass'),
            ],
        ]);

        $this->session = json_decode($res->getBody()->getContents());

   }

    public function salas()
    {
        $client = new Client();
        $res = $client->request('GET', config('salas.bookedHost') . '/Resources/', [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ],
        ]);
        return response()->json(json_decode($res->getBody()->getContents()));
    }

    /**
     * Return Json schedules list
     *
     * @return void
     */
    public function agendas()
    {
        $client = new Client();
        $res = $client->request('GET', config('salas.bookedHost') . '/Schedules/', [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ],
        ]);
        return response()->json(json_decode($res->getBody()->getContents()));
    }

    /**
     * Return Json reservations day list by schedule
     * @param integer $agenda
     * @return void
     */
    public function reservas($agenda)
    {
        $client = new Client();
        $parameters = '?scheduleId=' . $agenda . '&startDateTime=' . "'2020-10-27T00:00:00-0200'" . '&endDateTime=' . "'2020-10-27T23:59:59-0200'";
        $url = config('salas.bookedHost') . '/Reservations' . $parameters;
        //dd($url);
        $res = $client->request('GET', $url, [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ]
        ]);
        return response()->json(json_decode($res->getBody()->getContents()));
    }

    public function novaReserva()
    {
        $client = new Client();
        $res = $client->request('GET', config('salas.bookedHost') . '/Reservations/', [
            'headers' => [
                'X-Booked-SessionToken' => $this->session->sessionToken,
                'X-Booked-UserId' => $this->session->userId,
            ],
        ]);
        dd($res->getBody()->getContents());

    }
}
