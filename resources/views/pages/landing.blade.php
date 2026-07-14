@extends('layouts.fullscreen-layout')

@section('content')
    <div class="min-h-screen flex flex-col overflow-x-hidden bg-white dark:bg-gray-900">
        {{-- Hero Section --}}
        <section
            class="relative isolate min-h-[100svh] w-full overflow-hidden
        lg:min-h-screen
        bg-[linear-gradient(to_bottom,#E4EBF1_43%,#B5CFED_100%)]
        dark:bg-[linear-gradient(to_bottom,#1E293B,#0F172A)]">

            <div
                class="relative z-10 mx-auto flex min-h-[100svh] w-full max-w-[1600px] flex-col
        px-6 pb-28 pt-14

        sm:px-10 sm:pb-32 sm:pt-10

        lg:min-h-screen
        lg:px-16 lg:pb-36 lg:pt-12

        xl:px-24 xl:pt-14
        2xl:px-28">
                {{-- Logo --}}
                <div class="shrink-0">
                    <img src="/images/logo/logo.png" alt="BudGetIn Logo" class="w-28 sm:w-25 lg:w-52 xl:w-60">
                </div>

                {{-- Hero Content --}}
                <div
    class="mt-10 flex flex-1 flex-col
    lg:mt-5 lg:grid lg:items-center lg:gap-8 lg:py-4
    lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.1fr)]
    xl:grid-cols-[minmax(0,0.85fr)_minmax(0,1.15fr)]">

    {{-- Text --}}
    <div
        class="relative z-20 flex w-full flex-col items-start
        lg:max-w-1xl lg:gap-8 lg:pb-8">

        <h1
            class="max-w-[310px] text-[2rem] font-medium leading-[1.08]
            text-gray-800
            sm:max-w-[560px] sm:text-3xl
            lg:text-4xl
            xl:text-5xl
            dark:text-white/90">

            <span class="block">Take Control of Your</span>
            <span class="block">Finances,</span>
            <span class="block">Effortlessly</span>
        </h1>

        <p
            class="mt-9 max-w-[260px] text-sm font-light leading-[1.25]
            text-gray-800
            sm:max-w-2xl sm:text-base sm:leading-relaxed
            lg:mt-0 lg:text-lg
            dark:text-white/90">

            Track your income, manage expenses, and plan your financial
            future with a simple yet powerful platform.
        </p>

        {{-- Mobile Button --}}
        <a href="{{ route('login') }}"
            class="mt-10 inline-flex h-10 min-w-28
            items-center justify-center rounded-full
            bg-accent px-5 text-[0.65rem] font-medium text-white
            transition-colors hover:bg-accent-hover
            lg:hidden">

            Get Started
        </a>

        {{-- Desktop Button --}}
        <a href="{{ route('login') }}"
            class="hidden h-14 min-w-40 items-center justify-center
            rounded-full bg-accent px-8 text-sm font-medium text-white
            transition-colors hover:bg-accent-hover
            lg:inline-flex
            sm:text-base">

            Get Started
        </a>
    </div>

    {{-- Illustration --}}
    <div
        class="relative z-10 mt-0 flex w-full min-h-0
        items-center justify-end
        lg:mt-0 lg:justify-center">

        <img src="/images/landing/landing.png"
            alt="BudGetIn financial illustration"
            class="w-[78%] max-w-[320px] object-contain
            sm:max-h-[42vh] sm:w-full sm:max-w-[520px]
            lg:max-h-[64vh] lg:max-w-[720px]
            xl:max-h-[68vh] xl:max-w-[800px]
            2xl:max-w-[860px]">
    </div>
</div>
            </div>

            {{-- Bottom Curve: tidak diubah --}}
            <div class="absolute pointer-events-none absolute inset-x-0 -bottom-1 z-20
                h-16 overflow-hidden lg:hidden">

                <div
                    class="absolute -left-[60%] top-0
                h-40 w-[220%]
                rounded-[50%_50%_0_0/100%_100%_0_0]
                bg-[#E4E7EC]
                dark:bg-[#0B1E36]">
                </div>
            </div>
            <div
                class="pointer-events-none absolute left-1/2 z-20
                -bottom-16 h-[150px] w-[150%] -translate-x-1/2
                rounded-t-[90%]
                bg-[#E4E7EC]
                sm:-bottom-14 sm:h-[150px] sm:w-[150%]
                lg:-bottom-9 lg:h-[130px] lg:w-[150%]
                xl:h-[150px] xl:w-[140%]
                dark:bg-[#0B1E36]">
            </div>
        </section>
        <section
            class="flex h-auto flex-col items-center gap-10
        bg-[#E4E7EC] px-4 pb-20 pt-6
        sm:px-10
        lg:gap-14 lg:px-16
        dark:bg-[#0B1E36]">

            {{-- Heading --}}
            <div class="-mt-7 flex w-full max-w-4xl flex-col gap-3 sm:gap-5">
                <h2
                    class="text-center text-xl font-medium text-gray-800
                sm:text-3xl
                dark:text-white/90">
                    Struggling to manage your money and stay in control of your expenses?
                </h2>

                <p
                    class="text-center text-sm font-light text-gray-500
                sm:text-xl
                dark:text-gray-300">
                    Many people face the same challenges when managing their finances.
                </p>
            </div>

            {{-- Cards --}}
            <div
                class="grid w-full max-w-[1320px] grid-cols-2 gap-3
            sm:gap-6
            xl:grid-cols-4 xl:gap-10">

                {{-- Card 1 --}}
                <div
                    class="group h-56 w-full rounded-2xl border border-gray-200
                bg-white/90 p-4 transition hover:bg-main
                sm:h-75 sm:rounded-3xl sm:px-10 sm:py-12
                dark:border-white/10 dark:bg-dark
                dark:hover:bg-dark-hover">

                    <div class="flex h-full flex-col">

                        {{-- Icon --}}
                        <div
                            class="flex size-9 shrink-0 items-center justify-center
                        rounded-full bg-purple-600/10 transition
                        group-hover:bg-third
                        sm:size-12
                        dark:bg-purple-500/30
                        dark:group-hover:bg-third">

                            <svg class="size-5 sm:size-[30px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">

                                <g fill="none" fill-rule="evenodd">
                                    <path
                                        d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />

                                    <path fill="#8A38F5"
                                        class="transition group-hover:fill-white/90
                                    dark:fill-[#a769f7]
                                    dark:group-hover:fill-white/90"
                                        d="M18 3a3 3 0 0 1 2.995 2.824L21 6v14a1 1 0 0 1-1.405.914l-.12-.062l-2.725-1.678l-2.726 1.678a1 1 0 0 1-.938.058l-.11-.058l-2.726-1.678l-2.726 1.678a1 1 0 0 1-1.517-.732L6 20v-6H4a1 1 0 0 1-.993-.883L3 13V5.5a2.5 2.5 0 0 1 2.336-2.495L5.5 3zm-3 9h-4a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2M5.5 5a.5.5 0 0 0-.5.5V12h1V5.5a.5.5 0 0 0-.5-.5M16 8h-5a1 1 0 0 0-.117 1.993L11 10h5a1 1 0 0 0 .117-1.993z" />
                                </g>
                            </svg>
                        </div>

                        <h3
                            class="mt-4 text-base font-semibold leading-tight
                        text-gray-800 transition
                        group-hover:text-white/90
                        sm:mt-6 sm:text-2xl
                        dark:text-white/90">
                            Losing Track of Expenses
                        </h3>

                        <p
                            class="mt-auto text-xs font-light leading-tight
                        text-gray-700 transition
                        group-hover:text-gray-100
                        sm:text-base
                        dark:text-gray-300">
                            You don't always know where your money goes every day.
                        </p>
                    </div>
                </div>

                {{-- Card 2 --}}
                <div
                    class="group h-56 w-full rounded-2xl border border-gray-200
                bg-white/90 p-4 transition hover:bg-main
                sm:h-75 sm:rounded-3xl sm:px-10 sm:py-12
                dark:border-white/10 dark:bg-dark
                dark:hover:bg-dark-hover">

                    <div class="flex h-full flex-col">

                        {{-- Icon --}}
                        <div
                            class="flex size-9 shrink-0 items-center justify-center
                        rounded-full bg-green-600/10 transition
                        group-hover:bg-third
                        sm:size-12
                        dark:bg-green-500/30
                        dark:group-hover:bg-third">

                            <svg class="size-5 sm:size-[30px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">

                                <path fill="#43A047"
                                    class="transition group-hover:fill-white/90
                                dark:fill-[#5bbb60]
                                dark:group-hover:fill-white/90"
                                    d="M9 8h6a1 1 0 0 0 0-2H9a1 1 0 0 0 0 2m.47-3.5a.5.5 0 0 0 .5.44H14a.51.51 0 0 0 .5-.44a9.2 9.2 0 0 1 1.2-3a.47.47 0 0 0-.07-.57a.5.5 0 0 0-.53-.18l-1.86.8a.23.23 0 0 1-.2 0a.25.25 0 0 1-.14-.13L12.46.31a.5.5 0 0 0-.92 0l-.44 1.11a.25.25 0 0 1-.14.13a.23.23 0 0 1-.2 0L8.9.75a.5.5 0 0 0-.56.13a.47.47 0 0 0-.07.57a9.2 9.2 0 0 1 1.2 3.05m5.97 4.62a.58.58 0 0 0-.36-.12H8.92a.58.58 0 0 0-.36.12c-2.55 2-5.4 5.4-5.4 8.4C3.16 21.75 5.52 24 12 24s8.84-2.25 8.84-6.48c0-3-2.84-6.45-5.4-8.4M13 20.13a.26.26 0 0 0-.21.25v.37a.75.75 0 0 1-1.5 0v-.32a.25.25 0 0 0-.25-.25h-.59a.75.75 0 0 1 0-1.5h2.15a.67.67 0 0 0 .25-1.3l-2.18-.87a2.16 2.16 0 0 1 .33-4.14a.26.26 0 0 0 .21-.25v-.37a.75.75 0 0 1 1.5 0v.32a.25.25 0 0 0 .25.25h.59a.75.75 0 0 1 0 1.5h-2.11a.67.67 0 0 0-.25 1.3l2.18.87a2.16 2.16 0 0 1-.37 4.14" />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 text-base font-semibold leading-tight
                        text-gray-800 transition
                        group-hover:text-white/90
                        sm:mt-6 sm:text-2xl
                        dark:text-white/90">
                            Overspending
                        </h3>

                        <p
                            class="mt-auto text-xs font-light leading-tight
                        text-gray-700 transition
                        group-hover:text-gray-100
                        sm:text-base
                        dark:text-gray-300">
                            It's easy to go over budget without realizing it.
                        </p>
                    </div>
                </div>

                {{-- Card 3 --}}
                <div
                    class="group h-56 w-full rounded-2xl border border-gray-200
                bg-white p-4 transition hover:bg-main
                sm:h-75 sm:rounded-3xl sm:px-10 sm:py-12
                dark:border-white/10 dark:bg-dark
                dark:hover:bg-dark-hover">

                    <div class="flex h-full flex-col">

                        {{-- Icon --}}
                        <div
                            class="flex size-9 shrink-0 items-center justify-center
                        rounded-full bg-red-500/10 transition
                        group-hover:bg-third
                        sm:size-12
                        dark:bg-red-500/30
                        dark:group-hover:bg-third">

                            <svg class="size-5 sm:size-[30px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">

                                <path fill="#F44336"
                                    class="transition group-hover:fill-white
                                dark:fill-[#f77066]
                                dark:group-hover:fill-white/90"
                                    d="M5 21q-.825 0-1.412-.587T3 19V3h2v16h16v2zm1-3V9h4v9zm5 0V4h4v14zm5 0v-5h4v5z" />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 text-base font-semibold leading-tight
                        text-gray-800 transition
                        group-hover:text-white/90
                        sm:mt-6 sm:text-2xl
                        dark:text-white/90">

                            <span class="block sm:whitespace-nowrap">
                                Messy Financial
                            </span>

                            <span class="block">
                                Records
                            </span>
                        </h3>

                        <p
                            class="mt-auto text-xs font-light leading-tight
                        text-gray-700 transition
                        group-hover:text-gray-100
                        sm:text-base
                        dark:text-gray-300">
                            Notes and records are scattered and hard to manage.
                        </p>
                    </div>
                </div>

                {{-- Card 4 --}}
                <div
                    class="group h-56 w-full rounded-2xl border border-gray-200
                bg-white/90 p-4 transition hover:bg-main
                sm:h-75 sm:rounded-3xl sm:px-10 sm:py-12
                dark:border-white/10 dark:bg-dark
                dark:hover:bg-dark-hover">

                    <div class="flex h-full flex-col">

                        {{-- Icon --}}
                        <div
                            class="flex size-9 shrink-0 items-center justify-center
                        rounded-full bg-blue-600/10 transition
                        group-hover:bg-third
                        sm:size-12
                        dark:bg-blue-500/30
                        dark:group-hover:bg-third">

                            <svg class="size-5 sm:size-[30px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">

                                <path fill="#365CF4"
                                    class="transition group-hover:fill-white
                                dark:fill-[#6683f7]
                                dark:group-hover:fill-white/90"
                                    d="M10 16q2.5 0 4.25-1.75T16 10t-1.75-4.25T10 4T5.75 5.75T4 10t1.75 4.25T10 16m-.712-3.287Q9 12.425 9 12V7q0-.425.288-.712T10 6t.713.288T11 7v5q0 .425-.288.713T10 13t-.712-.288m-3.5 0Q5.5 12.426 5.5 12V9q0-.425.288-.712T6.5 8t.713.288T7.5 9v3q0 .425-.288.713T6.5 13t-.712-.288m7 0Q12.5 12.426 12.5 12v-2q0-.425.288-.712T13.5 9t.713.288t.287.712v2q0 .425-.288.713T13.5 13t-.712-.288M10 18q-3.35 0-5.675-2.325T2 10t2.325-5.675T10 2t5.675 2.325T18 10q0 1.4-.437 2.65t-1.238 2.275L21.3 19.9q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-4.975-4.975q-1.025.8-2.275 1.238T10 18" />
                            </svg>
                        </div>

                        <h3
                            class="mt-4 text-base font-semibold leading-tight
                        text-gray-800 transition
                        group-hover:text-white/90
                        sm:mt-6 sm:text-2xl
                        dark:text-white/90">
                            No Clear Insights
                        </h3>

                        <p
                            class="mt-auto text-xs font-light leading-tight
                        text-gray-700 transition
                        group-hover:text-gray-100
                        sm:text-base
                        dark:text-gray-300">
                            You can't clearly see your financial habits or trends.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section
            class="relative flex w-full items-center overflow-hidden
        border border-gray-200 bg-main
        px-5 py-6
        sm:px-8 sm:py-8
        lg:px-12 lg:py-10
        xl:px-20
        dark:border-white/10 dark:bg-[#071A2F]">

            <div
                class="relative mx-auto grid w-full max-w-[1500px]
            grid-cols-1 overflow-hidden rounded-3xl
            border border-gray-200 bg-fourth
            px-5 py-7
            sm:px-8 sm:py-8
            lg:min-h-[610px]
            lg:grid-cols-[56%_44%]
            lg:px-10 lg:py-9
            xl:min-h-[630px]
            xl:px-12
            dark:border-white/10 dark:bg-dark">

                {{-- Left Content --}}
                <div class="relative z-30 flex min-w-0 flex-col">

                    {{-- Main Heading --}}
                    <div class="max-w-[720px]">
                        <h2
                            class="text-xl font-semibold leading-[1.12]
                        text-gray-800
                        sm:text-[1.55rem]
                        lg:text-[1.7rem]
                        xl:text-[1.8rem]
                        dark:text-white/90">

                            Take control of your finances and build a smarter
                            financial future with confidence.
                        </h2>

                        <p
                            class="mt-4 max-w-[530px] text-justify
                        text-xs font-light leading-relaxed
                        text-gray-700
                        sm:text-[0.9rem]
                        lg:text-[0.95rem]
                        dark:text-gray-300">

                            BudGetIn simplifies how you manage your money. Track expenses,
                            understand your spending, and stay organized all in one
                            intuitive platform. Gain clear insights to make smarter
                            financial decisions every day.
                        </p>
                    </div>

                    {{-- Features --}}
                    <div
                        class="mt-7 flex flex-col gap-5
                    sm:mt-8 sm:gap-6
                    lg:mt-9 lg:gap-7">

                        {{-- Feature 1 --}}
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div
                                class="flex size-8 shrink-0 items-center justify-center
                            rounded-full bg-purple-600/10
                            sm:size-9
                            dark:bg-purple-500/30">

                                <svg class="size-[18px] sm:size-[21px]" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24">

                                    <g fill="none" fill-rule="evenodd">
                                        <path
                                            d="m12.594 23.258l-.012.002l-.071.035l-.02.004l-.014-.004l-.071-.036q-.016-.004-.024.006l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.016-.018m.264-.113l-.014.002l-.184.093l-.01.01l-.003.011l.018.43l.005.012l.008.008l.201.092q.019.005.029-.008l.004-.014l-.034-.614q-.005-.019-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.003-.011l.018-.43l-.003-.012l-.01-.01z" />

                                        <path fill="#8A38F5" class="dark:fill-[#a769f7]"
                                            d="M18 3a3 3 0 0 1 2.995 2.824L21 6v14a1 1 0 0 1-1.405.914l-.12-.062l-2.725-1.678l-2.726 1.678a1 1 0 0 1-.938.058l-.11-.058l-2.726-1.678l-2.726 1.678a1 1 0 0 1-1.517-.732L6 20v-6H4a1 1 0 0 1-.993-.883L3 13V5.5a2.5 2.5 0 0 1 2.336-2.495L5.5 3zm-3 9h-4a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2M5.5 5a.5.5 0 0 0-.5.5V12h1V5.5a.5.5 0 0 0-.5-.5M16 8h-5a1 1 0 0 0-.117 1.993L11 10h5a1 1 0 0 0 .117-1.993z" />
                                    </g>
                                </svg>
                            </div>

                            <div class="min-w-0 max-w-[450px]">
                                <h3
                                    class="text-sm font-semibold leading-tight
                                text-gray-800
                                sm:text-lg
                                dark:text-white/90">

                                    Smart Financial Tracking
                                </h3>

                                <p
                                    class="mt-1.5 text-justify text-xs font-light
                                leading-relaxed text-gray-700
                                sm:text-[0.9rem]
                                dark:text-gray-300">

                                    Easily record your income and expenses in real-time.
                                    Stay updated with your financial activity and never
                                    lose track of where your money goes.
                                </p>
                            </div>
                        </div>

                        {{-- Feature 2 --}}
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div
                                class="flex size-8 shrink-0 items-center justify-center
                            rounded-full bg-orange-500/10
                            sm:size-9
                            dark:bg-orange-500/30">

                                <svg class="size-[18px] sm:size-[21px]" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">

                                    <path fill="#F17400" class="dark:fill-[#ff8e25]"
                                        d="M14 2.75a.75.75 0 0 1 .75-.75h2.5a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0v-.69l-4.47 4.47a.75.75 0 0 1-1.06 0L8.5 6.56l-4.22 4.22a.75.75 0 1 1-1.06-1.06l4.75-4.75a.75.75 0 0 1 1.06 0l2.47 2.47l3.94-3.94h-.69a.75.75 0 0 1-.75-.75M3.75 14a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0v-2.5a.75.75 0 0 1 .75-.75m4.75-2.25a.75.75 0 0 0-1.5 0v5.5a.75.75 0 0 0 1.5 0zM11.75 13a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5a.75.75 0 0 1 .75-.75m4.75-3.25a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0z" />
                                </svg>
                            </div>

                            <div class="min-w-0 max-w-[450px]">
                                <h3
                                    class="text-sm font-semibold leading-tight
                                text-gray-800
                                sm:text-lg
                                dark:text-white/90">

                                    Personalized Financial Growth
                                </h3>

                                <p
                                    class="mt-1.5 text-justify text-xs font-light
                                leading-relaxed text-gray-700
                                sm:text-[0.9rem]
                                dark:text-gray-300">

                                    Understand your financial habits and discover
                                    opportunities to improve. BudGetIn helps you make
                                    smarter decisions based on your own spending patterns.
                                </p>
                            </div>
                        </div>

                        {{-- Feature 3 --}}
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div
                                class="flex size-8 shrink-0 items-center justify-center
                            rounded-full bg-green-600/10
                            sm:size-9
                            dark:bg-green-500/30">

                                <svg class="size-[21px] sm:size-[25px]" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 54 54" fill="none">

                                    <path
                                        d="M23.9565 34.4215C26.4475 34.4215 28.5648 33.4873 30.3084 31.6188C32.0521 29.7504 32.9239 27.4815 32.9239 24.8123C32.9239 22.1431 32.0521 19.8742 30.3084 18.0058C28.5648 16.1373 26.4475 15.2031 23.9565 15.2031C21.4656 15.2031 19.3483 16.1373 17.6046 18.0058C15.861 19.8742 14.9891 22.1431 14.9891 24.8123C14.9891 27.4815 15.861 29.7504 17.6046 31.6188C19.3483 33.4873 21.4656 34.4215 23.9565 34.4215ZM23.9565 37.6246C20.6187 37.6246 17.7914 36.3834 15.4749 33.901C13.1583 31.4186 12 28.3891 12 24.8123C12 21.2355 13.1583 18.206 15.4749 15.7236C17.7914 13.2412 20.6187 12 23.9565 12C27.2944 12 30.1216 13.2412 32.4382 15.7236C34.7548 18.206 35.913 21.2355 35.913 24.8123C35.913 26.3071 35.6953 27.7217 35.2599 29.0564C34.8245 30.391 34.2077 31.6055 33.4096 32.6999L40.8451 40.6675C41.1191 40.9611 41.2561 41.3348 41.2561 41.7886C41.2561 42.2423 41.1191 42.616 40.8451 42.9096C40.5711 43.2033 40.2224 43.3501 39.7989 43.3501C39.3755 43.3501 39.0267 43.2033 38.7527 42.9096L31.3173 34.942C30.296 35.7962 29.1626 36.4571 27.9171 36.9247C26.6716 37.3924 25.3514 37.6256 23.9565 37.6246Z"
                                        fill="#43A047" class="dark:fill-[#5bbb60]" />
                                </svg>
                            </div>

                            <div class="min-w-0 max-w-[450px]">
                                <h3
                                    class="text-sm font-semibold leading-tight
                                text-gray-800
                                sm:text-lg
                                dark:text-white/90">

                                    Simple &amp; Organized Management
                                </h3>

                                <p
                                    class="mt-1.5 text-justify text-xs font-light
                                leading-relaxed text-gray-700
                                sm:text-[0.9rem]
                                dark:text-gray-300">

                                    Keep all your financial data neat and structured.
                                    Categorize transactions, monitor your balance, and
                                    manage everything effortlessly in one place.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Illustration --}}
                <div
                    class="relative mt-0 flex min-h-[290px]
                items-end justify-center overflow-hidden
                sm:min-h-[380px]
                lg:mt-0 lg:min-h-[520px]">

                    {{-- Background Circle Left --}}
                    <div
                        class="absolute left-[22%] top-[10%]
        size-[115px] rounded-full
        bg-pink-300/30
        sm:size-[170px]
        lg:left-[8%] lg:top-[6%] lg:size-[220px]
        xl:size-[240px]
        dark:bg-pink-400/10">
                    </div>

                    {{-- Background Circle Right --}}
                    <div
                        class="absolute right-[22%] top-[10%]
        size-[115px] rounded-full
        bg-amber-200/30
        sm:size-[170px]
        lg:right-[8%] lg:top-[6%] lg:size-[220px]
        xl:size-[240px]
        dark:bg-amber-400/10">
                    </div>

                    {{-- Female --}}
                    <img src="/images/landing/female.png" alt="Female"
                        class="relative z-10
                    max-h-[255px] w-auto max-w-[82%]
                    object-contain object-bottom
                    sm:max-h-[340px]
                    lg:max-h-[485px] lg:max-w-full
                    xl:max-h-[510px]">

                    {{-- Weekly Report --}}
                    <img src="/images/landing/weekly-report.png" alt="Weekly Report"
                        class="absolute right-1 top-[39%] z-20
                    w-[78px] rounded-md
                    shadow-[4px_4px_8px_rgba(0,0,0,0.25)]
                    sm:right-4 sm:w-[110px]
                    lg:right-[-12px] lg:top-[39%] lg:w-[145px]
                    xl:right-[-20px] xl:w-[145px]">

                    {{-- Budgets --}}
                    <img src="/images/landing/budgets.png" alt="Budgets"
                        class="absolute bottom-0 left-1 z-20
                    w-[170px] rounded-md
                    shadow-[4px_4px_8px_rgba(0,0,0,0.25)]
                    sm:left-5 sm:w-[240px]
                    lg:bottom-[-5px] lg:left-[-80px] lg:w-[350px]
                    xl:left-[-50px] xl:w-[340px]">
                </div>
            </div>
        </section>
        <footer
            class="flex w-full flex-col items-center justify-center
        gap-5 px-5 py-12
        sm:gap-10 sm:px-6 sm:py-28">

            {{-- Logo --}}
            <a href="/">
                <img src="/images/logo/logo.png" alt="Logo" class="w-36 sm:w-60">
            </a>

            {{-- Description --}}
            <p
                class="w-full max-w-[310px] text-center
            text-xs font-light leading-relaxed
            text-gray-500
            sm:max-w-3xl sm:text-base
            dark:text-gray-400">

                Take control of your finances with BudGetIn. Easily track your income and expenses,
                manage your budget effectively, and gain meaningful insights to make smarter
                financial decisions every day.
            </p>

            {{-- Social Media --}}
            <div class="flex gap-2 sm:gap-3">

                {{-- Facebook --}}
                <a href="#" aria-label="Facebook" class="inline-flex items-center justify-center">

                    <svg class="size-9 sm:size-[45px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 55"
                        fill="none">

                        <rect opacity="0.25" x="0.75" y="0.75" width="53.5" height="53.5" rx="26.75"
                            stroke="black" stroke-width="1.5" class="dark:stroke-gray-300" />

                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M24.7511 38.0774V27.4995H22.5641V23.8542H24.7511V21.6656C24.7511 18.6917 25.9859 16.9233 29.4944 16.9233H32.4152V20.5691H30.5895C29.2237 20.5691 29.1334 21.0785 29.1334 22.0293L29.1284 23.8538H32.4359L32.0489 27.4991H29.1284V38.0774H24.7511Z"
                            fill="black" class="dark:fill-gray-300" />
                    </svg>
                </a>

                {{-- LinkedIn --}}
                <a href="#" aria-label="LinkedIn" class="inline-flex items-center justify-center">

                    <svg class="size-9 sm:size-[45px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 55"
                        fill="none">

                        <rect opacity="0.25" x="0.75" y="0.75" width="53.5" height="53.5" rx="26.75"
                            stroke="black" stroke-width="1.5" class="dark:stroke-gray-300" />

                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M22.5641 36.6667H18.3334V23.9744H22.5641V36.6667V36.6667Z" fill="black"
                            class="dark:fill-gray-300" />

                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M20.4362 21.1542H20.4121C19.1496 21.1542 18.3334 20.2136 18.3334 19.038C18.3334 17.8376 19.1745 16.9233 20.461 16.9233C21.7475 16.9233 22.5397 17.8376 22.5641 19.038C22.5641 20.2136 21.7475 21.1542 20.4362 21.1542V21.1542Z"
                            fill="black" class="dark:fill-gray-300" />

                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M38.0768 36.6663H33.9194V30.0319C33.9194 28.3655 33.3178 27.2284 31.8128 27.2284C30.6642 27.2284 29.98 27.995 29.6796 28.7354C29.5696 29.0007 29.5426 29.3704 29.5426 29.7411V36.6667H25.3846C25.3846 36.6667 25.4394 25.4295 25.3846 24.2659H29.5426V26.0224C30.0944 25.178 31.0827 23.9744 33.2901 23.9744C36.0259 23.9744 38.0769 25.7471 38.0769 29.5561L38.0768 36.6663V36.6663Z"
                            fill="black" class="dark:fill-gray-300" />
                    </svg>
                </a>

                {{-- Instagram --}}
                <a href="#" aria-label="Instagram" class="inline-flex items-center justify-center">

                    <svg class="size-9 sm:size-[45px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 55 55"
                        fill="none">

                        <rect opacity="0.25" x="0.75" y="0.75" width="53.5" height="53.5" rx="26.75"
                            stroke="black" stroke-width="1.5" class="dark:stroke-gray-300" />

                        <path
                            d="M23.2139 18H31.6139C34.8139 18 37.4139 20.6 37.4139 23.8V32.2C37.4139 33.7383 36.8028 35.2135 35.7151 36.3012C34.6274 37.3889 33.1521 38 31.6139 38H23.2139C20.0139 38 17.4139 35.4 17.4139 32.2V23.8C17.4139 22.2617 18.0249 20.7865 19.1127 19.6988C20.2004 18.6111 21.6756 18 23.2139 18ZM23.0139 20C22.0591 20 21.1434 20.3793 20.4683 21.0544C19.7932 21.7295 19.4139 22.6452 19.4139 23.6V32.4C19.4139 34.39 21.0239 36 23.0139 36H31.8139C32.7687 36 33.6843 35.6207 34.3595 34.9456C35.0346 34.2705 35.4139 33.3548 35.4139 32.4V23.6C35.4139 21.61 33.8039 20 31.8139 20H23.0139ZM32.6639 21.5C32.9954 21.5 33.3133 21.6317 33.5478 21.8661C33.7822 22.1005 33.9139 22.4185 33.9139 22.75C33.9139 23.0815 33.7822 23.3995 33.5478 23.6339C33.3133 23.8683 32.9954 24 32.6639 24C32.3324 24 32.0144 23.8683 31.78 23.6339C31.5456 23.3995 31.4139 23.0815 31.4139 22.75C31.4139 22.4185 31.5456 22.1005 31.78 21.8661C32.0144 21.6317 32.3324 21.5 32.6639 21.5ZM27.4139 23C28.74 23 30.0117 23.5268 30.9494 24.4645C31.8871 25.4021 32.4139 26.6739 32.4139 28C32.4139 29.3261 31.8871 30.5979 30.9494 31.5355C30.0117 32.4732 28.74 33 27.4139 33C26.0878 33 24.816 32.4732 23.8783 31.5355C22.9407 30.5979 22.4139 29.3261 22.4139 28C22.4139 26.6739 22.9407 25.4021 23.8783 24.4645C24.816 23.5268 26.0878 23 27.4139 23ZM27.4139 25C26.6182 25 25.8552 25.3161 25.2926 25.8787C24.7299 26.4413 24.4139 27.2044 24.4139 28C24.4139 28.7956 24.7299 29.5587 25.2926 30.1213C25.8552 30.6839 26.6182 31 27.4139 31C28.2095 31 28.9726 30.6839 29.5352 30.1213C30.0978 29.5587 30.4139 28.7956 30.4139 28C30.4139 27.2044 30.0978 26.4413 29.5352 25.8787C28.9726 25.3161 28.2095 25 27.4139 25Z"
                            fill="black" class="dark:fill-gray-300" />
                    </svg>
                </a>
            </div>

            {{-- Copyright --}}
            <p
                class="text-center text-[0.65rem] font-light
            text-gray-400
            sm:text-sm
            dark:text-gray-500">

                © 2026 BudGetIn. Built for smarter financial management.
            </p>
        </footer>
        {{-- Toggler --}}
        <div class="fixed right-6 bottom-6 z-50">
            <button
                class="bg-main-hover hover:bg-main inline-flex size-14 items-center justify-center rounded-full text-white transition-colors"
                @click.prevent="$store.theme.toggle()">
                <svg class="hidden fill-current dark:block" width="20" height="20" viewBox="0 0 20 20"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915C9.58577 4.2915 9.24998 3.95572 9.24998 3.5415V2.2915C9.24998 1.87729 9.58577 1.5415 9.99998 1.5415ZM10.0009 6.79327C8.22978 6.79327 6.79402 8.22904 6.79402 10.0001C6.79402 11.7712 8.22978 13.207 10.0009 13.207C11.772 13.207 13.2078 11.7712 13.2078 10.0001C13.2078 8.22904 11.772 6.79327 10.0009 6.79327ZM5.29402 10.0001C5.29402 7.40061 7.40135 5.29327 10.0009 5.29327C12.6004 5.29327 14.7078 7.40061 14.7078 10.0001C14.7078 12.5997 12.6004 14.707 10.0009 14.707C7.40135 14.707 5.29402 12.5997 5.29402 10.0001ZM15.9813 5.08035C16.2742 4.78746 16.2742 4.31258 15.9813 4.01969C15.6884 3.7268 15.2135 3.7268 14.9207 4.01969L14.0368 4.90357C13.7439 5.19647 13.7439 5.67134 14.0368 5.96423C14.3297 6.25713 14.8045 6.25713 15.0974 5.96423L15.9813 5.08035ZM18.4577 10.0001C18.4577 10.4143 18.1219 10.7501 17.7077 10.7501H16.4577C16.0435 10.7501 15.7077 10.4143 15.7077 10.0001C15.7077 9.58592 16.0435 9.25013 16.4577 9.25013H17.7077C18.1219 9.25013 18.4577 9.58592 18.4577 10.0001ZM14.9207 15.9806C15.2135 16.2735 15.6884 16.2735 15.9813 15.9806C16.2742 15.6877 16.2742 15.2128 15.9813 14.9199L15.0974 14.036C14.8045 13.7431 14.3297 13.7431 14.0368 14.036C13.7439 14.3289 13.7439 14.8038 14.0368 15.0967L14.9207 15.9806ZM9.99998 15.7088C10.4142 15.7088 10.75 16.0445 10.75 16.4588V17.7088C10.75 18.123 10.4142 18.4588 9.99998 18.4588C9.58577 18.4588 9.24998 18.123 9.24998 17.7088V16.4588C9.24998 16.0445 9.58577 15.7088 9.99998 15.7088ZM5.96356 15.0972C6.25646 14.8043 6.25646 14.3295 5.96356 14.0366C5.67067 13.7437 5.1958 13.7437 4.9029 14.0366L4.01902 14.9204C3.72613 15.2133 3.72613 15.6882 4.01902 15.9811C4.31191 16.274 4.78679 16.274 5.07968 15.9811L5.96356 15.0972ZM4.29224 10.0001C4.29224 10.4143 3.95645 10.7501 3.54224 10.7501H2.29224C1.87802 10.7501 1.54224 10.4143 1.54224 10.0001C1.54224 9.58592 1.87802 9.25013 2.29224 9.25013H3.54224C3.95645 9.25013 4.29224 9.58592 4.29224 10.0001ZM4.9029 5.9637C5.1958 6.25659 5.67067 6.25659 5.96356 5.9637C6.25646 5.6708 6.25646 5.19593 5.96356 4.90303L5.07968 4.01915C4.78679 3.72626 4.31191 3.72626 4.01902 4.01915C3.72613 4.31204 3.72613 4.78692 4.01902 5.07981L4.9029 5.9637Z"
                        fill="" />
                </svg>
                <svg class="fill-current dark:hidden" width="20" height="20" viewBox="0 0 20 20" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266C17.5538 11.1551 17.1885 11.1934 16.944 11.4207L17.4547 11.97ZM8.0306 2.5459L8.57989 3.05657C8.80718 2.81209 8.84554 2.44682 8.67398 2.16046C8.50243 1.8741 8.16227 1.73559 7.83948 1.82066L8.0306 2.5459ZM12.9154 13.0035C9.64678 13.0035 6.99707 10.3538 6.99707 7.08524H5.49707C5.49707 11.1823 8.81835 14.5035 12.9154 14.5035V13.0035ZM16.944 11.4207C15.8869 12.4035 14.4721 13.0035 12.9154 13.0035V14.5035C14.8657 14.5035 16.6418 13.7499 17.9654 12.5193L16.944 11.4207ZM16.7295 11.7789C15.9437 14.7607 13.2277 16.9586 10.0003 16.9586V18.4586C13.9257 18.4586 17.2249 15.7853 18.1799 12.1611L16.7295 11.7789ZM10.0003 16.9586C6.15734 16.9586 3.04199 13.8433 3.04199 10.0003H1.54199C1.54199 14.6717 5.32892 18.4586 10.0003 18.4586V16.9586ZM3.04199 10.0003C3.04199 6.77289 5.23988 4.05695 8.22173 3.27114L7.83948 1.82066C4.21532 2.77574 1.54199 6.07486 1.54199 10.0003H3.04199ZM6.99707 7.08524C6.99707 5.52854 7.5971 4.11366 8.57989 3.05657L7.48132 2.03522C6.25073 3.35885 5.49707 5.13487 5.49707 7.08524H6.99707Z"
                        fill="" />
                </svg>
            </button>
        </div>
    </div>
@endsection
