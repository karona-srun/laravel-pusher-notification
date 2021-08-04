<?php

namespace App\Http\Controllers;

use App\Events\MyEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $data = [
        //     "status" => "success",
        //     "message" => "My Event (Send pusher notification)"
        // ];
        // event(new MyEvent($data));
        // return view('home');

        $notifications = auth()->user()->unreadNotifications;
        return view('home', compact('notifications'));
    }

    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->id, function ($query) use ($request) {
                return $query->where('id', $request->id);
            })
            ->markAsRead();

        return response()->noContent();
    }

    public function sendNotification(){
        $user = auth()->user();//\App\Models\User::find(1);
           $details = [
                    'email' => $user->email,
                    'name' => $user->name,
                    'link' =>url(route('home' , $user->slug)),
                    'greeting' => 'Hi Artisan',
                    'body' => 'This is our example notification tutorial',
                    'thanks' => 'Thank you for visiting codechief.org!',
            ];
            event(new \App\Notifications\NewUserNotification($details));
            $user->notifyNow(new \App\Notifications\NewUserNotification($details));
      
        return redirect('/home');
    }
}
