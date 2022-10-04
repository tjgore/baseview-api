<div>
    <p>Hello {{ $invite->first_name }},</p>
    <p>You have been invited to join {{ $invite->school->name }}.<br/> 
    Click <a href="{{$inviteLink}}">here</a> to accept the invite or copy and paste the following link <a href="{{ $inviteLink }}">{{ $inviteLink }}</a></p>
    <br/>
    <p>Thank you, <br/> {{ config('app.name') }}</p>
</div>