@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
@endsection

@section('header-item')
@if(Auth::check())
<form class="logout-form" action="/logout" method="post">
    @csrf
    <button class="logout-link">logout</button>
</form>
@endif
@endsection



@section('content')
<div class="admin__content">
    <div class="admin__heading">
        <h2 class="admin__title">Admin</h2>
    </div>
    <!-- 検索機能 -->
    <form class="form" action="/admin" method="get">
        @csrf
        <div class="form__group">
            <input class="form__group-input--keyword" type="text" name="keyword" value="" placeholder="名前やメールアドレスを入力してください">
            <select name="gender" id="">
                <option hidden value="">性別</option>
                <option value="">全て</option>
                <option value="1">男性</option>
                <option value="2">女性</option>
                <option value="3">その他</option>
            </select>
            <select name="category_id" id="">
                <option hidden value="">お問い合わせの種類</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->content }}</option>
                @endforeach
            </select>
            <input class="form__group-input--date" type="date" name="until">
            <div class="form__button">
                <button class="form__button-submit" type="submit">検索</button>
            </div>
            <div class="form__button--fix">
                <button class="form__button-submit--back" type="submit" name="reset" value="reset">リセット</button>
            </div>
        </div>
    </form>

    <div class="form-sub">
        <button class="csv">エクスポート</button>
        <div class="paginate">
        {{ $contacts->appends(request()->query())->links() }}
        </div>
    </div>

    <table class="admin-table">
        <tr class="admin-table__row">
            <th class="admin-table__header">お名前</th>
            <th class="admin-table__header">性別</th>
            <th class="admin-table__header">メールアドレス</th>
            <th class="admin-table__header">お問い合わせの種類</th>
            <th class="admin-table__header"></th>
        </tr>
        @foreach ($contacts as $contact)
        <tr class="admin-table__row">
            <td class="admin-table__description">{{ $contact->last_name }}&nbsp;{{ $contact->first_name }}</td>
            <td class="admin-table__description">
                @if ($contact->gender == '1')
                {{'男性'}}
                @elseif ($contact->gender == '2')
                {{'女性'}}
                @else
                {{'その他'}}
                @endif
            </td>
            <td class="admin-table__description">{{$contact->email}}</td>
            <td class="admin-table__description">{{$contact->category->content}}</td>

            <!-- モーダル -->
            <td class="modal-button">
                <button class="modal-button__item">詳細</button>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection