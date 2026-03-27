<div class="deznav" style="margin-top: 30px">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            @if(Auth::guard('admin')->check())
            {{-- Registration Section --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text" data-i18n="Registration">Registration</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="/dashboard/register/1" data-i18n="Registration (Level 1)">Registration (Level 1)</a></li>
                    <li><a href="/dashboard/register/2" data-i18n="Registration (Level 2)">Registration (Level 2)</a></li>
                </ul>
            </li>

            {{-- Ticket Sales Section --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text" data-i18n="Ticket Sales">Ticket Sales</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="/dashboard/events/ticket" data-i18n="Event Tickets">Event Tickets</a></li>
                    <li>
                        <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Dinner Tickets</a>
                        <ul aria-expanded="false">
                            <li><a href="/dashboard/dinner-tickets">Sales Tickets</a></li>
                            <li>
                                <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Present Tickets</a>
                                <ul aria-expanded="false">
                                    <li><a href="{{ route('admin.sponsor.index', 'now') }}">Now Present</a></li>
                                    <li><a href="{{ route('admin.sponsor.index', 'past') }}">Past Present</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            {{-- Running Events Section --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-calendar"></i>
                    <span class="nav-text" data-i18n="Events">Events</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{ route('events.index', 'now') }}">Now Event</a></li>
                    <li><a href="{{ route('events.index', 'coming') }}">Coming Event</a></li>
                    <li><a href="{{ route('events.index', 'past') }}">Past Event</a></li>
                </ul>
            </li>

            {{-- Dinner Management Section --}}
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text" data-i18n="Dinner">Dinner</span>
                </a>
                <ul aria-expanded="false">
                    {{-- We pass 'now' for active (1) and 'past' for inactive (0) --}}
                    <li><a href="{{ route('admin.dinner.manage', 'now') }}">Now Dinner</a></li>
                    <li><a href="{{ route('admin.dinner.manage', 'past') }}">Past Dinner</a></li>
                    <li><a href="{{ route('staff.scanner') }}">Scanner</a></li>
                </ul>
            </li>
            <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text" data-i18n="Sponsor">Sponsor</span>
                </a>
                <ul aria-expanded="false">
                    {{-- We pass 'now' for active (1) and 'past' for inactive (0) --}}
                    <!-- <li><a href="{{ route('admin.sponsor.index', 'now') }}">Now Sponsor</a></li>
                    <li><a href="{{ route('admin.sponsor.index', 'past') }}">Past Sponsor</a></li> -->
                </ul>
            </li>
            <!-- <li>
                <a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                    <i class="flaticon-381-layer-1"></i>
                    <span class="nav-text" data-i18n="Sponsor">Sponsor</span>
                </a>
                <ul aria-expanded="false">
                    {{-- We pass 'now' for active (1) and 'past' for inactive (0) --}}
                    <li><a href="{{ route('admin.sponsor.index', 'now') }}">Now Sponsor</a></li>
                    <li><a href="{{ route('admin.sponsor.index', 'past') }}">Past Sponsor</a></li>
                </ul>
            </li> -->
            @endif
            @if(Auth::guard('agent')->check())
                <li>
                    <a class="ai-icon" href="{{ route('agent.tickets') }}" aria-expanded="false">
                        <i class="flaticon-381-home"></i>
                        <span class="nav-text">Event Tickets</span>
                    </a>
                </li>

                <!-- <li>
                    <a class="ai-icon" href="{{ route('staff.scanner') }}" aria-expanded="false">
                        <i class="flaticon-381-view"></i>
                        <span class="nav-text">QR Scanner</span>
                    </a>
                </li> -->
            @endif
        </ul>
    </div>
    
    <!-- <div class="deznav-footer">
        <a href="https://coreui.w3itexperts.com/?theme=Tixia" target="_blank" class="btn btn-docs btn-success w-100">
            <span>Docs & Components</span>
            <i class="fa-solid fa-arrow-up"></i>
        </a>
    </div> -->
</div>