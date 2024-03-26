<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Contact;
use App\Models\Category;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    // お問い合わせフォーム表示
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    // 確認画面への送信
    public function confirm(ContactRequest $request)
    {
        $contact = $request->only(['last_name', 'first_name', 'gender', 'email', 'tel1', 'tel2', 'tel3', 'address', 'building', 'category_id', 'detail']);
        // return $contact;
        // dd($contact);
        // return $contact;
        return view('confirm', compact('contact'));
    }

    // データベースへの登録
    public function store(ContactRequest $request)
    {
        if($request->input('back') == 'back')
        {
            return redirect('/')->withInput();
        }
        $contact = $request->only(['last_name', 'first_name', 'gender', 'email', 'tel', 'address', 'building', 'category_id', 'detail']);
        $contact['tel'] = $request->tel1 . $request->tel2 . $request->tel3;
        Contact::create($contact);
        return view('thanks');
    }

    // 検索機能
    public function search (Request $request)
    {
        if($request->input('reset') == 'reset'){
            return redirect('/admin');
        }
        $query = Contact::query();
        $keyword = $request->input('keyword');
        $gender = $request->input('gender');
        $category_id = $request->input('category_id');
        $until = $request->input('until');

        if(!empty($gender))
        {
            $query->where('gender', 'like', $gender)->get();
        }
        if(!empty($category_id))
        {
            $query->where('category_id', 'like', $category_id)->get();
        }
        if(!empty($until))
        {
            $query->whereDate('created_at', '<=', $until)->get();
        }
        if(!empty($keyword))
        {
            // $query->Where('last_name','like','%'.$keyword.'%')->orWhere('first_name','like','%'.$keyword.'%')->orWhereRaw('CONCAT(last_name, "", first_name) LIKE ? ', '%' . $keyword . '%')->orWhere('email','like','%'.$keyword.'%');

            $query->where(function($q) use ($keyword) {
                return $q->where('last_name','like','%'.$keyword.'%')->orWhere('first_name','like','%'.$keyword.'%')->orWhereRaw('CONCAT(last_name, "", first_name) LIKE ? ', '%' . $keyword . '%')->orWhere('email','like','%'.$keyword.'%')->get();
            });



            // $query->Where('last_name','like','%'.$keyword.'%')->orWhere(function ($q) use ($keyword) {
                // $q->Where('first_name','like','%'.$keyword.'%');
                // $q->orWhere('email','like','%'.$keyword.'%');
            // });
        }
        $categories = Category::all();
        $gender_list = Contact::all();
        // $contacts = $query->get()->paginate(7);
        $contacts = $query->orderBy('id', 'asc')->paginate(7);
        return view('admin', compact('contacts' , 'categories',));
    }
    
}
