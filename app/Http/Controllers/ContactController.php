<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreContactRequest;
use App\Http\Responses\CustomJsonResponse;
use App\Http\Responses\CustomErrorJsonResponse;

class ContactController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $sortBy = [
            ['bookmark', 'desc'],
            ['created_at', 'desc'],
        ];
        return (new CustomJsonResponse(Auth::user()->contacts()->sortBy($sortBy)->values()))->get();
    }

    /**
     * Get the passed resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Contact $contact)
    {
        $contact = Auth::user()->contacts()->where('id', $contact->id)->pop();

        if ($contact->count() === 0) {
            $code = Response::HTTP_NOT_FOUND;

            return (new CustomErrorJsonResponse(Response::$statusTexts[$code], $code))->get();
        }

        return (new CustomJsonResponse($contact))->get();
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

        return (new CustomJsonResponse(['id' => $contact->id]))->get();
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

    /**
     * Bookmark the specified resource and unbookmark all previous ones.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function bookmark(Contact $contact)
    {
        $contact = Auth::user()->contacts()->where('id', $contact->id)->pop();

        if ($contact->count() === 0) {
            $code = Response::HTTP_NOT_FOUND;

            return (new CustomErrorJsonResponse(Response::$statusTexts[$code], $code))->get();
        }

        Contact::where('user_id', Auth::user()->id)->update(['bookmark' => false]);

        $contact->bookmark = !$contact->bookmark;
        $contact->save();

        return (new CustomJsonResponse(['id' => $contact->id]))->get();
    }

    /**
     * Star or unstar the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function star(Contact $contact)
    {
        $contact = Auth::user()->contacts()->where('id', $contact->id)->pop();

        if ($contact->count() === 0) {
            $code = Response::HTTP_NOT_FOUND;

            return (new CustomErrorJsonResponse(Response::$statusTexts[$code], $code))->get();
        }

        $contact->star = !$contact->star;
        $contact->save();

        return (new CustomJsonResponse(['id' => $contact->id]))->get();
    }
}
