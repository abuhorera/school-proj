@extends('layouts.app')

@section('title', 'Classroom Guide')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-book-open text-primary mr-2"></i> Classroom Management Guide</h3>
            <p class="text-muted mb-0 small">How the module works &mdash; what each screen does and who can do what.</p>
        </div>
        <div>
            <a href="{{ route('classrooms.rooms') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Classroom List
            </a>
        </div>
    </div>

    {{-- Sub Navigation --}}
    @include('classrooms.partials.subnav')

    {{-- Hero Section --}}
    <div class="card border-0 shadow-sm mb-4 text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 12px;">
        <div class="card-body p-4 p-md-5">
            <span class="badge badge-light text-primary font-weight-bold px-3 py-2 mb-3 text-uppercase" style="letter-spacing: 0.5px;">
                <i class="fas fa-door-open mr-1"></i> Classroom Management
            </span>
            <h2 class="font-weight-bold display-5 mb-3">Every room on campus &mdash; <span class="text-warning">who is in it, right now</span>.</h2>
            <p class="lead mb-4 text-light" style="max-width: 900px; font-size: 1.05rem; line-height: 1.6;">
                List your classrooms, labs and halls once. Give each section its <strong>home room</strong>, and the timetable you
                already have tells you who sits where, period by period &mdash; even where nobody typed a room. Find a free room in
                seconds, book it, and when a fan or a door breaks, <strong>report it, fix it and move the class</strong> without
                phone calls.
            </p>
            <div class="row pt-2 text-center text-md-left">
                <div class="col-6 col-md-3 mb-2">
                    <div class="border-left border-warning pl-3">
                        <div class="h3 font-weight-bold text-white mb-0">1</div>
                        <small class="text-light">list for every room</small>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="border-left border-warning pl-3">
                        <div class="h3 font-weight-bold text-white mb-0">0</div>
                        <small class="text-light">retyping of the timetable</small>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="border-left border-warning pl-3">
                        <div class="h3 font-weight-bold text-warning mb-0">Live</div>
                        <small class="text-light">"who is where" board</small>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="border-left border-warning pl-3">
                        <div class="h3 font-weight-bold text-white mb-0">1-click</div>
                        <small class="text-light">move a class out of broken room</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- The Big Picture Flow --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-project-diagram text-primary mr-2"></i> The Big Picture</h5>
        </div>
        <div class="card-body py-4">
            <div class="row text-center align-items-center">
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-primary mb-2"><i class="fas fa-door-open fa-2x"></i></div>
                        <h6 class="font-weight-bold mb-1">List your rooms</h6>
                        <small class="text-muted d-block">seats, facilities, building</small>
                    </div>
                </div>
                <div class="col-md-auto d-none d-md-block mb-3 text-muted">
                    <i class="fas fa-arrow-right fa-lg"></i>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-success mb-2"><i class="fas fa-th-large fa-2x"></i></div>
                        <h6 class="font-weight-bold mb-1">Seat each section</h6>
                        <small class="text-muted d-block">one home room per section</small>
                    </div>
                </div>
                <div class="col-md-auto d-none d-md-block mb-3 text-muted">
                    <i class="fas fa-arrow-right fa-lg"></i>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-info mb-2"><i class="fas fa-calendar-alt fa-2x"></i></div>
                        <h6 class="font-weight-bold mb-1">Timetable links</h6>
                        <small class="text-muted d-block">labs & rooms where typed</small>
                    </div>
                </div>
                <div class="col-md-auto d-none d-md-block mb-3 text-muted">
                    <i class="fas fa-arrow-right fa-lg"></i>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-warning mb-2"><i class="fas fa-search-location fa-2x"></i></div>
                        <h6 class="font-weight-bold mb-1">Find &amp; book</h6>
                        <small class="text-muted d-block">free rooms for extra classes</small>
                    </div>
                </div>
                <div class="col-md-auto d-none d-md-block mb-3 text-muted">
                    <i class="fas fa-arrow-right fa-lg"></i>
                </div>
                <div class="col-md-2 col-6 mb-3">
                    <div class="p-3 rounded bg-light border">
                        <div class="text-danger mb-2"><i class="fas fa-tools fa-2x"></i></div>
                        <h6 class="font-weight-bold mb-1">Report &amp; fix</h6>
                        <small class="text-muted d-block">repairs &amp; instant moves</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Feature Grid --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold text-dark">
                <i class="fas fa-bolt text-warning mr-2"></i> What It Does 
                <span class="badge badge-light border text-muted ml-2 font-weight-normal">Available Today</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-primary px-2 py-1 mr-2"><i class="fas fa-magic"></i></span>
                            <h6 class="mb-0 font-weight-bold">No Retyping <span class="badge badge-warning text-dark ml-1">STAR</span></h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Uses the timetable you already have. A period with no room typed counts as the section's home room, so the whole school's room use appears without filling a single cell again.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-info px-2 py-1 mr-2"><i class="fas fa-door-open"></i></span>
                            <h6 class="mb-0 font-weight-bold">One List For Every Room</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Classrooms, labs, halls and offices with everyday seats, exam seats, lab computers, facilities and step-free access. Quick setup creates a whole block in one go.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-secondary px-2 py-1 mr-2"><i class="fas fa-link"></i></span>
                            <h6 class="mb-0 font-weight-bold">Understands Old Room Names</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            "R1", "r-1" and "Room 1" typed over the years are grouped and pointed at the right room. Nothing in the timetable or datesheet is rewritten.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-success px-2 py-1 mr-2"><i class="fas fa-th-large"></i></span>
                            <h6 class="mb-0 font-weight-bold">Room Plan With History</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            A home room per section, per session. A mid-year move keeps the old room for past dates, and next year starts with "Copy from last year".
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-warning text-dark px-2 py-1 mr-2"><i class="fas fa-clock"></i></span>
                            <h6 class="mb-0 font-weight-bold">Knows Your Shifts</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Morning and evening sections may share a room. Two sections in one room at the same time are flagged, unless marked as a combined class.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-danger px-2 py-1 mr-2"><i class="fas fa-user-friends"></i></span>
                            <h6 class="mb-0 font-weight-bold">Seats vs Students</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            The Capacity report shows every section against its room's seats, flags tight and over-full rooms, and suggests a free room or a swap that fits.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-primary px-2 py-1 mr-2" style="background:#6f42c1;"><i class="fas fa-calendar-alt"></i></span>
                            <h6 class="mb-0 font-weight-bold">Room Timetable &amp; Door Sign</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Every lesson in a room across the week, and a printable sign for the door with the room's code, sections and weekly timetable grid.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-info px-2 py-1 mr-2"><i class="fas fa-tv"></i></span>
                            <h6 class="mb-0 font-weight-bold">Now Board <span class="badge badge-success ml-1">LIVE</span></h6>
                        </div>
                        <p class="text-muted small mb-0">
                            A live full-screen page for the office TV: every room free, in use (who, until when) or under repair. It auto-refreshes every minute.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-dark px-2 py-1 mr-2"><i class="fas fa-search-location"></i></span>
                            <h6 class="mb-0 font-weight-bold">Free Rooms in Seconds</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Pick a period or a time, seats and a facility like a projector. Accounts for lessons, free periods, exams, bookings and repairs, and shows "free until".
                        </p>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-success px-2 py-1 mr-2"><i class="fas fa-calendar-check"></i></span>
                            <h6 class="mb-0 font-weight-bold">Bookings With Your Rules</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Teachers ask, the office approves &mdash; or switch approval off. Weekly repeats skip dates that are taken, and only the office can book over a lesson.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-danger px-2 py-1 mr-2"><i class="fas fa-tools"></i></span>
                            <h6 class="mb-0 font-weight-bold">Repairs That Move Classes <span class="badge badge-danger ml-1">NEW</span></h6>
                        </div>
                        <p class="text-muted small mb-0">
                            Anyone reports a problem. The office gives the job to someone, marks the room out of use, and moves its sections with "Move for these dates".
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="p-3 rounded border h-100 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge badge-secondary px-2 py-1 mr-2"><i class="fas fa-th"></i></span>
                            <h6 class="mb-0 font-weight-bold">Usage &amp; Clashes</h6>
                        </div>
                        <p class="text-muted small mb-0">
                            A heatmap of how busy each room is across the week, and an automated report of double bookings and labs too small for the student class size.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- How to Use It (3 Columns) --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-list-ol text-primary mr-2"></i> How To Use It</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded h-100 bg-white">
                        <h6 class="font-weight-bold text-primary mb-3">
                            <span class="badge badge-primary rounded-circle mr-1">1</span> First-Time Setup (Office)
                        </h6>
                        <ol class="small pl-3 text-muted mb-0" style="line-height: 1.8;">
                            <li><strong>Buildings:</strong> Add campus blocks/wings (code like <b>A</b> becomes room prefix).</li>
                            <li><strong>Classroom List:</strong> Press <b>Quick setup</b>, choose floors and rooms per floor, then <b>Create rooms</b>.</li>
                            <li>Open each room to set seats, exam seats, and special facilities.</li>
                            <li><strong>Room Labels:</strong> Reconcile legacy typed timetable codes to physical rooms.</li>
                            <li><strong>Room Plan:</strong> Pick a base home room for every section and press <b>Save room plan</b>.</li>
                        </ol>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded h-100 bg-white">
                        <h6 class="font-weight-bold text-success mb-3">
                            <span class="badge badge-success rounded-circle mr-1">2</span> Everyday Operations
                        </h6>
                        <ol class="small pl-3 text-muted mb-0" style="line-height: 1.8;">
                            <li><strong>Need a room?</strong> Free Rooms &rarr; pick period/day &rarr; Find free rooms &rarr; Book.</li>
                            <li><strong>Pending requests:</strong> Red badge on Bookings counts them. Press <b>Approve</b> or <b>Reject</b>.</li>
                            <li><strong>Room maintenance:</strong> Open Maintenance, log ticket, assign staff, mark room unusable if needed.</li>
                            <li><strong>Temporary relocation:</strong> On Room Plan press <b>Move</b> to schedule dates (painting, repairs).</li>
                            <li>Keep the live timetable open to track period-by-period occupancy campus-wide.</li>
                        </ol>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="p-3 border rounded h-100 bg-white">
                        <h6 class="font-weight-bold text-info mb-3">
                            <span class="badge badge-info rounded-circle mr-1">3</span> For Teachers &amp; Staff
                        </h6>
                        <ol class="small pl-3 text-muted mb-0" style="line-height: 1.8;">
                            <li><strong>Free Rooms:</strong> Find empty spaces for extra classes or remedial sessions &amp; send booking request.</li>
                            <li>Follow the status of submitted requests in <b>Room Bookings</b>.</li>
                            <li><strong>Broken equipment?</strong> Press <b>Report a problem</b> with notes. Track assignment and fix status.</li>
                            <li>In timetable builder, sections automatically sit in their designated <b>Home Room</b> unless specified.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Permissions Matrix --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-shield text-primary mr-2"></i> Who Can Do What</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0 text-sm">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 40%;">Task / Capability</th>
                            <th class="text-center" style="width: 20%;">Office (School Admin)</th>
                            <th class="text-center" style="width: 20%;">Teachers</th>
                            <th class="text-center" style="width: 20%;">Other Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>See rooms, Room Plan, timetables, free rooms</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center text-muted">&mdash;</td>
                        </tr>
                        <tr>
                            <td>Add, edit and delete physical rooms &amp; buildings</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                        </tr>
                        <tr>
                            <td>Set and update the master Room Plan (home rooms)</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                        </tr>
                        <tr>
                            <td>Book a room directly</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes (Instant)</td>
                            <td class="text-center font-weight-bold text-info"><i class="fas fa-info-circle"></i> Request / Office Approves</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                        </tr>
                        <tr>
                            <td>Book over a lesson ("Force Book")</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                        </tr>
                        <tr>
                            <td>Report a room maintenance problem</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                        </tr>
                        <tr>
                            <td>Resolve assigned repair jobs</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                        </tr>
                        <tr>
                            <td>Mark rooms out of use &amp; schedule emergency temporary moves</td>
                            <td class="text-center font-weight-bold text-success"><i class="fas fa-check"></i> Yes</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                            <td class="text-center text-danger font-weight-bold"><i class="fas fa-times"></i> No</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

