<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreContactRequest;
use App\Http\Responses\CustomJsonResponse;
use App\Http\Responses\CustomErrorJsonResponse;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return (new CustomJsonResponse(Auth::user()->contacts()))->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();
        
        try {

            // create and persist the new contact form
            $contact = Contact::create([
                'name' => $validated['name'],
                'surname' => $validated['surname'],
                'email' => $validated['email'],
                'message' => $validated['message'],
                'user_id' => Auth::user()->id
            ]);

        } catch (Exception $e) {
            
            return (new CustomErrorJsonResponse($e->getMessage()))->get();
        }
        
        return (new CustomJsonResponse($contact))->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function delete(Contact $contact)
    {
        $deletedId = $contact->id;
        
        $contact->delete();

        return (new CustomJsonResponse(['id' => $deletedId]))->get();
    }
}
