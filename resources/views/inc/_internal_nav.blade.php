<ul class="sub-nav">
    <li><a href="{{route('timesheets.edit', $client->id)}}" class="sub-nav-link {{ Route::is('timesheets.edit') ? "under" : "" }}">Przedziały godzinowe |</a></li>
    <li><a href="{{route('projects.edit', $client->id)}}" class="sub-nav-link {{ Route::is('projects.edit') ? "under" : "" }}">Projekty |</a></li>
    <li><a href="{{route('work_hours.edit', $client->id)}}" class="sub-nav-link {{ Route::is('work_hours.edit') ? "under" : "" }}">Roboczogodziny |</a></li>
    <li><a href="{{route('clients.edit', $client->id)}}" class="sub-nav-link {{ Route::is('clients.edit') ? "under" : "" }}">Podsumowanie</a></li>
</ul>


