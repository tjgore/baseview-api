<div>
    <p>Hello {{ $invite->first_name }},</p>
    <br/>
    <p>You have been invited to join {{ $invite->school->name }}. 
    Click <a href="{{$inviteLink}}">here</a> to accept the invite or copy and paste the following link <a href="{{ $inviteLink }}">{{ $inviteLink }}</a></p>
    <br/><br/>
    <p>Thank you, <br/> {{ config('app.name') }}</p>
</div>