<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\AreaDisabledDay;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    public function getReservations()
    {
        $array = ['error' => '', 'list' => []];
        $daysHelper = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sáb'];

        $areas = Area::where('allowed', true)->get();

        foreach ($areas as $area) {
            $dayList = explode(',', $area['days']);
            $dayGroups = [];

            $lastDay = intval(current($dayList));
            $dayGroups[] = $daysHelper[$lastDay];
            array_shift($dayList);

            foreach ($dayList as $day) {
                if (intval($day) != $lastDay + 1) {
                    $dayGroups[] = $daysHelper[$lastDay];
                    $dayGroups[] = $daysHelper[$day];
                }
                $lastDay = intval($day);
            }

            $dayGroups[] = $daysHelper[end($dayList)];

            $dates = '';
            $close = 0;
            foreach ($dayGroups as $group) {
                if ($close === 0) {
                    $dates .= $group;
                } else {
                    $dates .= '-' . $group . ',';
                }
                $close = 1 - $close;
            }

            $dates = explode(',', $dates);
            \array_pop($dates);

            $start = date('H:i', \strtotime($area['start']));
            $end = date('H:i', \strtotime($area['end']));

            foreach ($dates as $key => $value) {
                $dates[$key] .= ' ' . $start . ' às ' . $end;
            }

            $array['list'][] = [
                'id' => $area->id,
                'cover' => asset('storage/' . $area['cover']),
                'title' => $area->title,
                'dates' => $dates
            ];
        }

        return $array;
    }

    public function addMyReservations()
    {
        $array = ['error' => ''];
        return $array;
    }

    public function getDisabledDates($id)
    {
        $array = ['error' => '', 'list' => []];

        $area = Area::find($id);
        if ($area) {
            $disabledDays = AreaDisabledDay::where('area_id', $id)->get();

            foreach ($disabledDays as $day) {
                $array['list'][] = $day['day'];
            }

            $allowedDays = explode(',', $area['days']);
            $offDays = [];

            for ($i = 0; $i < 7; $i++) {
                if (!\in_array($i, $allowedDays)) {
                    $offDays[] = $i;
                }
            }

            $start = time();
            $end = \strtotime('+3 months');;

            for ($current = $start; $current < $end; $current = strtotime('+1 day', $current)) {
                $wd = date('w', $current);
                if (\in_array($wd, $offDays)) {
                    $array['list'][] = date('Y-m-d', $current);
                }
            }
        } else {
            $array['error'] = 'Área inexistente';
        }

        return $array;
    }

    public function getTimes($id, Request $request)
    {
        $array = ['error' => '', 'list' => []];
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d'
        ]);

        if (!$validator->fails()) {
            $date = $request->input('date');
            $area = Area::find($id);

            if ($area) {
                $can = true;
                $disabledDay = AreaDisabledDay::where('day', $date)->where('area_id', $id)->count();

                if ($disabledDay > 0) {
                    $can = false;
                }

                $allowedDays = explode(',', $area['days']);
                $wd = date('w', strtotime($date));

                if (!\in_array($wd, $allowedDays)) {
                    $can = false;
                }

                if ($can) {
                    $start = \strtotime($area['start']);
                    $end = \strtotime($area['end']);

                    $times = [];

                    for ($lastTime = $start; $lastTime < $end; $lastTime = \strtotime('+1 hour', $lastTime)) {
                        $times[] = $lastTime;
                    }

                    $timeList = [];

                    foreach ($times as $time) {
                        $timeList[] = [
                            'id' => date('H:i:s', $time),
                            'title' => date('H:i', $time) . '-' . date('H:i', strtotime('+1 hour', $time)),
                        ];
                    }

                    $reservations = Reservation::where('area_id', $id)->whereBetween('reservation_date', [
                        $date . '00:00:00',
                        $date . '23:59:59',
                    ])->get();

                    $toRemove = [];
                    foreach ($reservations as $reservation) {
                        $toRemove[] = date('H:i:s', strtotime($reservation['reservation_date']));
                    }

                    foreach ($timeList as $timeItem) {
                        if (!\in_array($timeItem['id'], $toRemove)) {
                            $array['list'][] = $timeItem;
                        }
                    }
                }
            } else {
                $array['list'] = 'Área inexistente';
            }
        } else {
            $array['error'] = $validator->errors()->first();
        }

        return $array;
    }

    public function getMyReservations()
    {
        $array = ['error' => ''];
        return $array;
    }

    public function deleteMyReservations()
    {
        $array = ['error' => ''];
        return $array;
    }
}
