<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Order;
use App\Models\DriverRequest;
use App\Models\Driver;

class SearchRequestDriver implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order_id;
    protected $group_id;
    protected $pickup_latitude;
    protected $pickup_longitude;
    protected $pickup_location;
    protected $drop_latitude;
    protected $drop_longitude;
    protected $drop_location;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order_id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location)
    {
        $this->order_id = $order_id;
        $this->group_id = $group_id;
        $this->pickup_latitude = $pickup_latitude;
        $this->pickup_longitude = $pickup_longitude;
        $this->pickup_location = $pickup_location;
        $this->drop_latitude = $drop_latitude;
        $this->drop_longitude = $drop_longitude;
        $this->drop_location = $drop_location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::where('id', $this->order_id)->first();
        $group_id = $this->group_id;
        $pickup_latitude = $this->pickup_latitude;
        $pickup_longitude = $this->pickup_longitude;
        $pickup_location = $this->pickup_location;
        $drop_latitude = $this->drop_latitude;
        $drop_longitude = $this->drop_longitude;
        $drop_location = $this->drop_location;

        $driver_request = new DriverRequest;
        $driver_search_radius = 1000000000000000000;
        $sleep_time = 15;

        $pending_requests = DriverRequest::status(['pending'])->groupId([$group_id])->update(['status' => $driver_request->statusArray['cancelled']]);

        if ($order->driver_id && $order->driver) {
            // return response()->json(
            //     [
            //         'status_message'    => 'Request already accepted',
            //         'status_code'   => '0'
            //     ]
            // )->send();
            \Log::info("Request already accepted : ".$order->id);
            return;
        }
        $drivers = Driver::search($drop_latitude, $drop_longitude, $driver_search_radius, $group_id)->get();

        if ($drivers->count() == 0) {
            // return response()->json(
            //     [
            //         'status_message'    => 'Sorry, No drivers found.',
            //         'status_code'   => '0'
            //     ]
            // )->send();
            \Log::info("Sorry, No drivers found. : ".$order->id);
            return;
        }

        $nearest_driver = $drivers->first();

        $driver_request->order_id = $order->id;
        $driver_request->group_id = $group_id;
        $driver_request->driver_id = $nearest_driver->id;
        $driver_request->pickup_latitude = $pickup_latitude;
        $driver_request->pickup_longitude = $pickup_longitude;
        $driver_request->drop_latitude = $drop_latitude;
        $driver_request->pickup_location = $pickup_location;
        $driver_request->drop_longitude = $drop_longitude;
        $driver_request->drop_location = $drop_location;
        $driver_request->status = $driver_request->statusArray['pending'];
        $driver_request->save();

        $push_notification_title = "New order request received.";
        $push_notification_data  = [
            'type' => 'order_request',
            'request_id' => $driver_request->id,
            'request_data' => [
                'request_id' => $driver_request->id,
                'order_id' => $order->id,
                'pickup_location' => $pickup_location,
                'min_time' => '0',
                'pickup_latitude' => $pickup_latitude,
                'pickup_longitude' => $pickup_longitude
            ]
        ];
        
        push_notification($nearest_driver->user->device_type, $push_notification_title, $push_notification_data, $nearest_driver->user->type, $nearest_driver->user->device_id);
        SearchRequestDriver::dispatch($order->id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location)
                ->delay(now()->addSeconds(15));
    }
}
