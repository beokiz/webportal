@php
    $item = $data['item'];
    $domains = $data['domains']->keyBy('id');

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
                    Screening wurde eingereicht
                </h1>
            </div>
        </div>

        <div class="result-evaluation-col result-evaluation-col-title">
            <p>
                <span class="tw-font-black">Bezeichner des Screenings</span>:
                {{$item->uuid}}
            </p>
        </div>

        <div class="result-evaluation-col result-evaluation-col-content">
            <div class="domains-list-container">
                @foreach($domains as $domain)
                    <div class="domains-list-container">
                        <h3 class="{{ !empty($ratingData[$domain->id]['color']) ? $ratingData[$domain->id]['color'] : '' }}">
                            {{$domain->name}}
                        </h3>

                        @foreach($domain->subdomains as $subdomain)
                            <div class="subdomains-list-container">
                                <div class="subdomains-list-head">
                                    <h4>{{$subdomain->name}}</h4>

                                    @foreach(['Noch Nicht', 'Ansatzweise', 'Weitgehend', 'Zuverlassig'] as $heading)
                                        <div class="radio-wrap">
                                            <span>{{$heading}}</span>
                                        </div>
                                    @endforeach
                                </div>

                                @foreach($subdomain->milestones as $milestone)
                                    <div class="milestone-list-container">
                                        <h5>
                                            {{$milestone->abbreviation}}
                                        </h5>

                                        <div class="milestone-list-text">
                                            <span>{{$milestone->title}}</span>
                                            <p>{{$milestone->text}}</p>
                                        </div>

                                        <fieldset>
                                            @foreach([1, 2, 3, 4] as $rating)
                                                <div class="radio-wrap radio-content">
                                                    @if($ratingData[$domain->id]['milestones'][$milestone->id] == $rating)
                                                        <input type="radio" value="{{$rating}}" checked/>
                                                    @else
                                                        <input type="radio" value="{{$rating}}" disabled="disabled"/>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </fieldset>
                                    </div>
                                @endforeach

                            </div>
                        @endforeach

                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
