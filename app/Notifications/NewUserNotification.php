<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $details;
   
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    // public function __construct($details)
    // {
    //     $this->details = $details;
    // }

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        return (new MailMessage)
                    ->greeting($this->details['greeting'])
                    ->line($this->details['name'])
                    ->line($this->details['email'])
                    ->line($this->details['body'])
                    ->line($this->details['link'])
                    ->line($this->details['thanks']);
    }

    /**
     * Get the array representation of the notification.
     *                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        // return [
        //     'name' => $this->name,
        //     'email' => $this->email,
        // ];
        
        return [
            'data' => $this->details['body'],
            'name' => $this->details['name'],
            'email' => $this->details['email'],
            'link' => $this->details['link'],                                 
            'thanks' => $this->details['thanks']
        ];
    }
}
