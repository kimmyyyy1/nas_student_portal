<div wire:poll.5s>
    {{-- ⚡ RENEWAL / CONTINUING ENROLLMENT BANNER ⚡ --}}
    @if($student->status === 'Pending Renewal')
        <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg overflow-hidden border border-emerald-400/30">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20 blur-2xl"></div>
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                <div class="text-white text-center sm:text-left">
                    <h3 class="text-lg sm:text-xl font-extrabold mb-1 flex items-center justify-center sm:justify-start gap-2">
                        <i class='bx bxs-check-circle text-2xl text-yellow-300'></i> 
                        Renewal Application Submitted!
                    </h3>
                    <p class="text-emerald-100 text-xs sm:text-sm font-medium text-balance">Your application is currently being reviewed by the Registrar's Office. Please wait for further updates.</p>
                </div>
                <div class="flex-shrink-0 bg-white/15 backdrop-blur-sm text-white font-bold py-3 px-6 rounded-xl border border-white/20 text-xs flex items-center gap-2">
                    <i class='bx bx-time text-lg'></i> Pending Review
                </div>
            </div>
        </div>
    @elseif($student->status === 'Renewal (Returned)')
        <div class="relative bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-lg overflow-hidden border border-red-400/30 animate-pulse">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20 blur-2xl"></div>
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                <div class="text-white text-center sm:text-left">
                    <h3 class="text-lg sm:text-xl font-extrabold mb-1 flex items-center justify-center sm:justify-start gap-2">
                        <i class='bx bxs-error-circle text-2xl text-yellow-300'></i> 
                        Action Required: Renewal Needs Revision!
                    </h3>
                    <p class="text-red-500 text-xs sm:text-sm font-black bg-white/90 px-3 py-1 rounded-lg inline-block mt-1">The Registrar has returned your application for corrections. Please check the remarks.</p>
                </div>
                <a href="{{ route('student.renew-enrollment') }}" wire:navigate class="flex-shrink-0 bg-white hover:bg-red-50 text-red-600 font-black py-3 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105 active:scale-95 text-sm flex items-center gap-2">
                    Update Documents <i class='bx bx-right-arrow-alt text-lg'></i>
                </a>
            </div>
        </div>
    @elseif((in_array($student->promotion_status, ['Promoted', 'Conditional']) || ($student->promotion_status && str_contains($student->promotion_status, 'Honors')) || $student->status === 'Continuing' || $student->status === 'Renewal (Returned)') && $student->status !== 'Enrolled')
        <div class="relative bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg overflow-hidden border border-indigo-400/30">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mt-20 -mr-20 blur-2xl"></div>
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 relative z-10">
                <div class="text-white text-center sm:text-left">
                    <h3 class="text-lg sm:text-xl font-extrabold mb-1 flex items-center justify-center sm:justify-start gap-2">
                        <i class='bx bxs-graduation text-2xl text-yellow-300'></i> 
                        Ready for the Next School Year?
                    </h3>
                    
                    @if($isEnrollmentOpen)
                        <p class="text-indigo-100 text-xs sm:text-sm font-medium">Please submit your updated documents to renew your NASCENT SAS scholarship and enroll.</p>
                    @else
                        <p class="text-indigo-200 text-xs sm:text-sm font-medium">
                            <i class='bx bx-time-five'></i> Enrollment period is currently closed. It will open from <strong class="text-white">{{ $displayStartDate }}</strong> to <strong class="text-white">{{ $displayEndDate }}</strong>.
                        </p>
                    @endif
                </div>

                @if($isEnrollmentOpen)
                    <a href="{{ route('student.renew-enrollment') }}" wire:navigate class="flex-shrink-0 bg-white hover:bg-indigo-50 text-indigo-700 font-bold py-3 px-8 rounded-xl shadow-lg transition-all transform hover:scale-105 active:scale-95 text-sm flex items-center gap-2 group">
                        Proceed to Renewal <i class='bx bx-right-arrow-alt text-lg group-hover:translate-x-1 transition-transform'></i>
                    </a>
                @else
                    <button disabled class="flex-shrink-0 cursor-not-allowed bg-white/10 backdrop-blur-sm text-indigo-200 font-bold py-3 px-8 rounded-xl border border-white/20 text-sm flex items-center gap-2">
                        <i class='bx bxs-lock-alt text-lg'></i> Enrollment Closed
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
