
<h2>CCRP Soils Data Platform</h2>

<p>{{ $info['creator_name'] }} {{ t("has invited you to join the group:") }} {{ $info['name_project']}}.</p>
<p><a href="{{URL::to('/en/confirm-project/'.$info['project_id'].'/'.$info['user_id'].'/'.$info['key_confirmed'])}}">{{ t("Go here to accept your invitation</a> or ") }}<a href={{$info['url']}}> {{ t("visit the group</a> to learn more.") }}</p>