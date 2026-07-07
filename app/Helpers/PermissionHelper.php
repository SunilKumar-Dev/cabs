<?php

use Carbon\Carbon;


if (!function_exists('formatPermissions')) {

    function formatPermissions($permissions)
    {
        $grouped = [];

        $actions = [
            'viewAny'     => 'View List',
            'view'        => 'View',
            'create'      => 'Create',
            'edit'        => 'Edit',
            'update'      => 'Update',
            'delete'      => 'Delete',
            'status'      => 'Change Status',
        ];

        foreach ($permissions as $permission) {

            foreach ($actions as $key => $label) {

                if (str_starts_with($permission->name, $key . '_')) {

                    $module = str_replace($key . '_', '', $permission->name);

                    $grouped[$module][] = $label;

                    break;
                }
            }
        }

        $html = '';

        foreach ($grouped as $module => $items) {


            $html .= '<div class="rounded p-3 mb-3">';
            $html .= '<h6 class="font-semibold mb-2 ">' . ucfirst($module) . '</h6>';

            foreach ($items as $item) {
                $html .= '
                    <span class="badge bg-primary">
                        [ ' .  $item . ' ]
                    </span>
                ';
            }

            $html .= '</div>';
        }

        return $html;
    }
}



    function getStatusColor($status)
    {
        return match ($status) {
            'Active' => 'bg-green-100 text-green-700 border-green-300',
            'Inactive' => 'bg-gray-100 text-gray-700 border-gray-300',
            default => '',
        };
    }



    function vehicle_status_badge($status)
    {
        $classes = getStatusColor($status); 

        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ' . $classes . '">' . $status . '</span>';
    }   


function licenseExpiryStatus($date)
{
    if (!$date) {
        return null;
    }

    $daysLeft = (int) now()->diffInDays(Carbon::parse($date), false);

    return match (true) {
        $daysLeft < 0 => [
            'text' => 'Expired',
            'class' => 'px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700',
        ],

        $daysLeft === 0 => [
            'text' => 'Expiring today',
            'class' => 'px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700',
        ],

        $daysLeft <= 30 => [
            'text' => "Expiring in {$daysLeft} days",
            'class' => 'px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700',
        ],

        default => null,
    };
}