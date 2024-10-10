@php
    $item = $data['item'];
    $kita = $data['kita'];
    $domains = $data['domains']->keyBy('id')->toArray();

    if ($item->age) {
        $localPreparedDomains = array_map(function ($domain) use ($item) {
            return [
                'id' => $domain['id'],
                'name' => $domain['name'],
                'subdomains' => array_filter(array_map(function ($subdomain) use ($item) {
                    return [
                        'id' => $subdomain['id'],
                        'name' => $subdomain['name'],
                        'milestones' => array_filter($subdomain['milestones'], function ($milestone) use ($item) {
                            return floatval($milestone['age']) === floatval($item->age);
                        }),
                    ];
                }, $domain['subdomains']), function ($subdomain) {
                    return count($subdomain['milestones']) > 0;
                }),
            ];
        }, $domains);

        $domains = array_filter($localPreparedDomains, function ($domain) {
            return count($domain['subdomains']) > 0;
        });
    }

    $ratingData = [];

    foreach ($item->data as $domain) {
        $domainMilestones = [];

        foreach ($domain['milestones'] as $milestones) {
            $domainMilestones[$milestones['id']] = $milestones['value'];
        }

        $ratingData[$domain['domain']] = [
            'domain' => $domain['domain'],
            'color' => $domain['color'],
            'milestones' => $domainMilestones,
        ];
    }
@endphp

<div class="result-evaluation-container">
    <div class="result-evaluation-row result-evaluation-domains">
        <div class="result-evaluation-col result-evaluation-col-head">
            <div class="tw-text-center">
                <h1 class="tw-uppercase text-primary tw-font-black tw-text-xl tw-mb-8">
                    Einschätzung wurde eingereicht
                </h1>
            </div>
        </div>

        <div class="result-evaluation-col result-evaluation-col-title">
            <p>
                <span class="tw-font-black">Bezeichner des Einschätzung</span>:
                {{ $kita['formatted_name'] }}_{{ $item['custom_unique_id'] }}
            </p>
        </div>

        <div class="result-evaluation-col result-evaluation-col-content">
            <div class="domains-list-container">
                @foreach($domains as $domain)
                    <div class="domains-list-container">
                        <h3 class="{{ !empty($ratingData[$domain['id']]['color']) ? $ratingData[$domain['id']]['color'] : '' }}">
                            {{ $domain['name'] }}
                        </h3>

                        @foreach($domain['subdomains'] as $subdomain)
                            <div class="subdomains-list-container">
                                <div class="subdomains-list-head">
                                    <h4>{{$subdomain['name']}}</h4>

                                    @foreach(['Noch Nicht', 'Ansatzweise', 'Weitgehend', 'Zuverlassig'] as $heading)
                                        <div class="radio-wrap">
                                            <span>{{$heading}}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @if(!empty($subdomain['milestones']))
                                    @foreach($subdomain['milestones'] as $milestone)
                                        <div class="milestone-list-container">
                                            <h5>
                                                {{$milestone['abbreviation']}}
                                            </h5>

                                            <div class="milestone-list-text">
                                                <span>{{$milestone['title']}}</span>
                                                <p>{{$milestone['text']}}</p>
                                            </div>

                                            <fieldset>
                                                @foreach([1, 2, 3, 4] as $rating)
                                                    <div class="radio-wrap radio-content">
                                                        @if(!empty($ratingData[$domain['id']]['milestones'][$milestone['id']]) && $ratingData[$domain['id']]['milestones'][$milestone['id']] == $rating)
                                                            <input type="radio" value="{{$rating}}" checked/>
                                                        @else
                                                            <input type="radio" value="{{$rating}}" disabled="disabled"/>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </fieldset>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach

                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
