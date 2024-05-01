<template>
    <nav class="fixed bg-[#021b46] border-[#fdffff1a] top-0 z-50 sm:40 w-full navtop-color custom-box-shadow mb-12 ">
       <div v-show="isVisible" class="bg-[#0474CC] w-[100%] text-center py-1 flex justify-center items-center">
    <div class="flex text-center flex-row justify-center items-center gap-4 container mx-auto px-2">
        <span class="text-sm md:text-base text-indique" style="color: white; font-size:13px">Indique um amigo e ganhe R$ 15,00 de saldo real para cada amigo que convidar 💥</span>
        <button @click.prevent="registerToggle" class="botao-resgatar px-1 py-1 bg-[#ffffff] rounded-md text-[#03C6FC] flex justify-center items-center" style="font-size: 14px;height: 25px;border-radius: 3px;padding-left: .30rem;padding-right: .30rem;}">
            <p>Resgatar</p>
        </button>
    </div>
    <div class="fechar-indique" @click="closeBanner">&#10005;</div>
</div>
        <div class="px-3 lg:px-5 lg:pl-3 nav-menu" style="background: linear-gradient(90deg, #000c20,#021b46);">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <div class="flex-row cursor-pointer items-center px-2 py-4 border-b-4 gap-2 border-[#a3d712] mr-[15px] hidden sm:flex justify-center">
                        <svg height="1em" viewBox="0 0 640 512" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M220.7 7.468C247.3-7.906 281.4 1.218 296.8 27.85L463.8 317.1C479.1 343.8 470 377.8 443.4 393.2L250.5 504.5C223.9 519.9 189.9 510.8 174.5 484.2L7.468 194.9C-7.906 168.2 1.218 134.2 27.85 118.8L220.7 7.468zM143.8 277.3C136.9 303.2 152.3 329.1 178.3 336.9C204.3 343.9 230.1 328.5 237.9 302.5L240.3 293.6C240.4 293.3 240.5 292.9 240.6 292.5L258.4 323.2L246.3 330.2C239.6 334 237.4 342.5 241.2 349.2C245.1 355.9 253.6 358.1 260.2 354.3L308.4 326.5C315.1 322.6 317.4 314.1 313.5 307.4C309.7 300.8 301.2 298.5 294.5 302.3L282.5 309.3L264.7 278.6C265.1 278.7 265.5 278.8 265.9 278.9L274.7 281.2C300.7 288.2 327.4 272.8 334.4 246.8C341.3 220.8 325.9 194.1 299.9 187.1L196.1 159.6C185.8 156.6 174.4 163.2 171.4 174.3L143.8 277.3z" fill="currentColor"></path><path d="M324.1 499L459.4 420.9C501.3 396.7 515.7 343.1 491.5 301.1L354.7 64.25C356.5 64.08 358.2 64 360 64H584C614.9 64 640 89.07 640 120V456C640 486.9 614.9 512 584 512H360C346.4 512 333.8 507.1 324.1 499V499zM579.8 135.7C565.8 123.9 545.3 126.2 532.9 138.9L528.1 144.2L523.1 138.9C510.6 126.2 489.9 123.9 476.4 135.7C460.7 149.2 459.9 173.1 473.9 187.6L522.4 237.6C525.4 240.8 530.6 240.8 533.9 237.6L582 187.6C596 173.1 595.3 149.2 579.8 135.7H579.8z" fill="currentColor" opacity="0.4"></path></svg>
                        <p class="text-base font-bold text-white">CASSINO</p>
                    </div>
                    <div class="flex-row cursor-pointer items-center opacity-50 px-2 py-4 border-b-4 gap-2 border-[#a3d712]/[.0] mr-[25px] hidden sm:flex justify-center">
                        <svg height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M355.5 45.53L342.4 14.98c-27.95-9.983-57.18-14.98-86.42-14.98c-29.25 0-58.51 4.992-86.46 14.97L156.5 45.53l99.5 55.13L355.5 45.53zM86.78 96.15L53.67 99.09c-34.79 44.75-53.67 99.8-53.67 156.5L.0001 256c0 2.694 .0519 5.379 .1352 8.063l24.95 21.76l83.2-77.67L86.78 96.15zM318.8 336L357.3 217.4L255.1 144L154.7 217.4l38.82 118.6L318.8 336zM512 255.6c0-56.7-18.9-111.8-53.72-156.5L425.6 96.16L403.7 208.2l83.21 77.67l24.92-21.79C511.1 260.1 512 258.1 512 255.6zM51.77 367.7l-7.39 32.46c33.48 49.11 82.96 85.07 140 101.7l28.6-16.99l-48.19-103.3L51.77 367.7zM347.2 381.5l-48.19 103.3l28.57 17c57.05-16.66 106.5-52.62 140-101.7l-7.38-32.46L347.2 381.5z" fill="currentColor"></path><path d="M458.3 99.08L458.3 99.08L458.3 99.08zM511.8 264c-1.442 48.66-16.82 95.87-44.28 136.1l-7.38-32.46l-113 13.86l-48.19 103.3l28.22 16.84c-23.48 6.78-47.67 10.2-71.85 10.2c-23.76 0-47.51-3.302-70.58-9.962l28.23-17.06l-48.19-103.3l-113-13.88l-7.39 32.46c-27.45-40.19-42.8-87.41-44.25-136.1l24.95 21.76l83.2-77.67L86.78 96.15L53.67 99.09c29.72-38.29 69.67-67.37 115.2-83.88l.3613 .2684L156.5 45.53l99.5 55.13l99.5-55.13L342.4 14.98c45.82 16.48 86 45.64 115.9 84.11L425.6 96.16L403.7 208.2l83.21 77.67L511.8 264zM357.3 217.4L255.1 144L154.7 217.4l38.82 118.6L318.8 336L357.3 217.4z" fill="currentColor" opacity="0.4"></path></svg>
                        <p class="text-base font-bold">ESPORTES</p>
                    </div>
                </div>
                <div class="container mx-auto flex flex-row justify-between">
                    <div class="flex flex-row items-center">
                    <button @click.prevent="toggleMenu" type="button" class="hidden sm:inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg height="24px" viewBox="0 0 448 512" width="19px" xmlns="http://www.w3.org/2000/svg"><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z" fill="white"></path></svg>
                    </button>
                    <a v-if="setting" href="/" class="flex ml-2 md:mr-24">
                        <div class="hidden sm:block">
                            <img :src="`/storage/`+setting.software_logo_black" alt="" class="h-6 mr-3 block dark:hidden " />
                            <img :src="`/storage/`+setting.software_logo_white" alt=""  class="h-6 mr-3 hidden dark:block" />
                        </div>
                        <div class="block sm:hidden">
                            <img :src="`/storage/`+setting.software_favicon" class="h-10 mr-3" alt="" />
                        </div>
                    </a>
                </div>  
                <div v-if="!simple" class="flex items-center py-3">

                    <div v-if="!isAuthenticated" class="flex ml-5 gap-4">
                        <button @click.prevent="registerToggle" class="ui-button ml-3 mr-3 rounded-md  gap-2 justify-center items-center" style="padding: .5rem .75rem; border: 1px solid #317fff;">{{ $t('Register') }}</button>
                        <button @click.prevent="loginToggle" class="ui-button-blue ml-3 mr-3 rounded-md flex flex-row gap-2 justify-center items-center" style="background-color: #03C6FC !important;">
                            <svg data-v-2b009606="" height="1em" viewBox="0 0 512 512" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M344.7 273.5l-144.1 136c-6.975 6.578-17.2 8.375-26 4.594C165.8 410.3 160.1 401.6 160.1 392V320H32.02C14.33 320 0 305.7 0 288V224c0-17.67 14.33-32 32.02-32h128.1V120c0-9.578 5.707-18.25 14.51-22.05c8.803-3.781 19.03-1.984 26 4.594l144.1 136C354.3 247.6 354.3 264.4 344.7 273.5z" fill="currentColor"></path><path d="M416 32h-64c-17.67 0-32 14.33-32 32s14.33 32 32 32h64c17.67 0 32 14.33 32 32v256c0 17.67-14.33 32-32 32h-64c-17.67 0-32 14.33-32 32s14.33 32 32 32h64c53.02 0 96-42.98 96-96V128C512 74.98 469 32 416 32z" fill="currentColor" opacity="0.4"></path></svg>
                            {{ $t('Log in') }}</button>
                    </div>


                    <div v-if="isAuthenticated" class="flex items-center">
                        <MakeDeposit :showMobile="false" :title="$t('Deposit')" />
                        <WalletBalance />
                        <!-- <LanguageSelector />
                        <DropdownDarkLight/> -->

                        <div class="flex items-center ml-3">
                            <div>
                                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" :src="userData?.avatar ? '/storage/'+userData.avatar : `/assets/images/profile.jpg`" alt="">
                                </button>
                            </div>
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-sm text-gray-900 dark:text-white" role="none">
                                        {{ userData?.name }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                        {{ userData?.email }}
                                    </p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <RouterLink :to="{ name: 'home' }" active-class="link-active" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <span class="w-8 h-8 mr-3">
                                                <i class="fa-duotone fa-house"></i>
                                            </span>
                                            {{ $t('Dashboard') }}
                                        </RouterLink>
                                    </li>
                                    <li>
                                        <RouterLink :to="{ name: 'profileAffiliate' }" active-class="profile-menu-active" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <span class="w-8 h-8 mr-3">
                                                <i class="fa-duotone fa-people-group"></i>
                                            </span>
                                            {{ $t('Affiliate') }}
                                        </RouterLink>
                                    </li>
                                    <li>
                                        <RouterLink :to="{ name: 'profileDeposit' }" active-class="profile-menu-active" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <span class="w-8 h-8 mr-3">
                                                <i class="fa-regular fa-money-bill-trend-up"></i>
                                            </span>
                                            {{ $t('Deposit') }}
                                        </RouterLink>
                                    </li>
                                    <li>
                                        <RouterLink :to="{ name: 'profileWithdraw' }" active-class="profile-menu-active" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <span class="w-8 h-8 mr-3">
                                                <i class="fa-light fa-money-bill-transfer"></i>
                                            </span>
                                            {{ $t('Withdraw') }}
                                        </RouterLink>
                                    </li>
                                    <li>
                                        <RouterLink :to="{ name: 'profileWallet' }" active-class="profile-menu-active" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <span class="w-8 h-8 mr-3">
                                              <i class="fa-duotone fa-wallet"></i>
                                            </span>
                                            {{ $t('My Wallet') }}
                                        </RouterLink>
                                    </li>
                                    <li>
                                        <a href="#" @click.prevent="profileToggle" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                                            <span class="w-8 h-8 mr-3">
                                               <i class="fa-regular fa-id-card-clip"></i>
                                            </span>
                                            {{ $t('My Profile') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a @click.prevent="logoutAccount" href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">
                                             <span class="w-8 h-8 mr-3">
                                               <i class="fa-duotone fa-right-from-bracket"></i>
                                            </span>
                                            {{ $t('Sign out') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                </div>
            </div>
        </div>

        <transition name="fade">
            <div v-if="showSearchMenu" class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center ">
                <div @click="toggleSearch" class="absolute inset-0 carousel_banners opacity-50 cursor-pointer"></div>

                <!-- Start searchbar action -->
                <div class="search-menu p-4 sm:ml-64">

                    <div class="mb-5 w-full">
                        <div class="md:w-4/6 2xl:w-4/6 mx-auto">
                            <div class="flex flex-col">
                                <div class="relative w-full">
                                    <input type="search"
                                           v-model="searchTerm"
                                           class="block dark:focus:border-green-500 p-2.5 w-full z-20 text-sm text-gray-900 input-color-primary rounded-lg border focus:outline-none dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                                           placeholder="Nome do jogo | Provedor"
                                           required>

                                    <button v-if="searchTerm.length > 0" @click.prevent="clearData" type="button" class="absolute top-0 end-0 h-full p-2.5 text-sm font-medium text-white rounded-e-lg dark:bg-[#1C1E22] ">
                                        <span class="">Recusar</span>
                                    </button>
                                </div>
                                <div class="text-center mt-4">
                                    <p>A pesquisa requer pelo menos 3 caracteres</p>
                                </div>
                            </div>

                            <div v-if="!isLoadingSearch" class="mt-8 grid grid-cols-3 md:grid-cols-6 gap-4 py-5">
                                <CassinoGameCard
                                    v-if="games"
                                    v-for="(game, index) in games?.data"
                                    :index="index"
                                    :title="game.game_name"
                                    :cover="game.cover"
                                    :gamecode="game.game_code"
                                    :type="game.distribution"
                                    :game="game"
                                />
                            </div>
                            <div v-else class="relative items-center block max-w-sm p-6 bg-white border border-gray-100 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-800 dark:hover:bg-gray-700">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white opacity-20">Noteworthy technology acquisitions 2021</h5>
                                <p class="font-normal text-gray-700 dark:text-gray-400 opacity-20">Here are the biggest enterprise technology acquisitions of 2021 so far, in reverse chronological order.</p>
                                <div role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
                                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End searchbar action -->


            </div>
        </transition>

    </nav>
    <div id="modalElAuth" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-screen md:h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-3xl max-h-full bg-base rounded-lg shadow-lg container-form">
            <div class="flex md:justify-between">
                <div class="w-full relative p-5 ">
                    <div v-if="isLoadingLogin" class="absolute top-0 left-0 right-0 bottom-0 bg-white/70 dark:bg-gray-800/70 z-[999]">
                        <div role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
                            <svg aria-hidden="true" class="w-10 h-10 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                    <form @submit.prevent="loginSubmit" method="post" action="" class="">
                        <div class="flex justify-between mb-6">
                           <img :src="`/storage/`+setting.software_logo_black" alt="logotipo" class="logotipo-form">
                            <a @click.prevent="loginToggle" href="">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>

                        <div class="relative mb-3"> 
                            <input required type="text" v-model="loginForm.email" name="email" class="input-group input-email" :placeholder="$t('E-mail ou nome de usuário')">
                        </div>

                        <div class="relative mb-6">
                            <input required :type="typeInputPassword"
                                   v-model="loginForm.password"
                                   name="password"
                                   class="input-group pr-[40px] input-senha"
                                   :placeholder="$t('Type the password')">
                            <button type="button" @click.prevent="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 botao-olho" style="height:47px;">
                                <i v-if="typeInputPassword === 'password'" class="fa-regular fa-eye"></i>
                                <i v-if="typeInputPassword === 'text'" class="fa-sharp fa-regular fa-eye-slash"></i>
                            </button>
							 <a href="/forgot-password" class="text-white text-sm esqueceu-senha">{{ $t('Forgot password') }}</a>
                        </div>
                       

                        <div class="mt-3 w-full">
                            <button type="submit" class="ui-button-blue rounded w-full mb-3 botao-submit" style="background-color: #03c6fc !important;">
                                {{ $t('Log in') }}
                            </button>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-300 mb-6 criar-conta">Ainda não tem uma conta?<a href="" @click.prevent="hideLoginShowRegisterToggle" class="criar-conta"><span>Criar uma conta grátis</span></a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modalElRegister" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-[999] hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-screen md:h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-3xl max-h-full bg-base rounded-lg shadow-lg container-form">
            <div v-if="isLoadingRegister" class="absolute top-0 left-0 right-0 bottom-0 bg-white/70 dark:bg-gray-800/70 z-[999]">
                <div role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
                    <svg aria-hidden="true" class="w-10 h-10 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <div class="flex md:justify-between h-full">
                <div class="w-full relative p-5 m-auto">
                    <form @submit.prevent="registerSubmit" method="post" action="" class="">
                        <div class="flex justify-between mb-6">
                          	<img :src="`/storage/`+setting.software_logo_black" alt="logotipo" class="logotipo-form">
                            <a @click.prevent="registerToggle" href="">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>

                        <div class="relative mb-3">
                            <input type="text"
                                   name="name"
                                   v-model="registerForm.name"
                                   class="input-group input-email"
                                   :placeholder="$t('Enter name')"
                                   required
                            >
                        </div>

                        <div class="relative mb-3">
                            <input type="email"
                                   name="email"
                                   v-model="registerForm.email"
                                   class="input-group input-email"
                                   :placeholder="$t('Enter email or phone')"
                                   required
                            >
                        </div>

                        <div class="relative mb-3">
                            <input type="text"
                                   name="cpf"
                                   v-model="registerForm.cpf"
                                   class="input-group input-email"
                                   :placeholder="$t('Enter cpf')"
                                   required
                            >
                        </div>

                        <div class="relative mb-3">
                            <input :type="typeInputPassword"
                                   name="password"
                                   v-model="registerForm.password"
                                   class="input-group pr-[40px] input-email"
                                   :placeholder="$t('Type the password')"
                                   required
                            >
                            <button type="button" @click.prevent="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 botao-olho">
                                <i v-if="typeInputPassword === 'password'" class="fa-regular fa-eye"></i>
                                <i v-if="typeInputPassword === 'text'" class="fa-sharp fa-regular fa-eye-slash"></i>
                            </button>
                        </div>

                        <div class="relative mb-3">
                            <input :type="typeInputPassword"
                                   name="password_confirmation"
                                   v-model="registerForm.password_confirmation"
                                   class="input-group pr-[40px] input-email"
                                   :placeholder="$t('Confirm the Password')"
                                   required
                            >
                            <button type="button" @click.prevent="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3.5 botao-olho">
                                <i v-if="typeInputPassword === 'password'" class="fa-regular fa-eye"></i>
                                <i v-if="typeInputPassword === 'text'" class="fa-sharp fa-regular fa-eye-slash"></i>
                            </button>
                        </div>
                        <div class="relative mb-3">
                            <input type="text"
                                   name="phone"
                                   v-maska
                                   data-maska="[
                                    '(##) ####-####',
                                    '(##) #####-####'
                                  ]"
                                   v-model="registerForm.phone"
                                   class="input-group input-email"
                                   :placeholder="$t('Enter your phone')"
                                   required
                            >
                        </div>

                        <div class="mb-3 mt-5">
                            <button @click.prevent="isReferral = !isReferral" type="button" class="flex justify-between w-full">
                                <p>{{ $t('Enter Referral/Promo Code') }}</p>
                                <div class="">
                                    <i v-if="isReferral" class="fa-solid fa-chevron-up"></i>
                                    <i v-if="!isReferral" class="fa-solid fa-chevron-down"></i>
                                </div>
                            </button>

                            <div v-if="isReferral" class="relative mb-3 mt-1">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                    <i class="fa-regular fa-user text-success-emphasis"></i>
                                </div>
                                <input type="text" name="name" v-model="registerForm.reference_code" class="input-group" :placeholder="$t('Code')">
                            </div>
                        </div>

                        <hr class="mb-3 mt-2 dark:border-gray-600">

                        <div class="mb-3 mt-11">
                            <div class="flex">
                                <input id="link-checkbox" v-model="registerForm.term_a" name="term_a" required type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="link-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $t('I agree to the User Agreement & confirm I am at least 18 years old') }}</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="flex items-center">
                                <input id="link-checkbox-b" v-model="registerForm.agreement" name="term_b" required type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="link-checkbox-b" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $t('I agree with the') }} <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">{{ $t('terms and conditions') }}</a>.</label>
                            </div>
                        </div>

                        <div class="mt-5 w-full">
                            <button type="submit" class="ui-button-blue rounded w-full mb-3">
                                {{ $t('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="modalProfileEl" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-screen md:h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl md:max-w-lg max-h-full bg-white dark:bg-gray-900 rounded-lg shadow-lg">
            <div v-if="!isLoadingProfile" class="flex flex-col">

                <!-- PROFILE HEADER -->
                <div class="flex justify-between w-full p-4">
                    <h1 class="text-2xl font-bold">{{ $t('User data') }}</h1>
                    <button @click.prevent="profileToggle" type="button" class="text-2xl">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- PROFILE BODY -->
                <div v-if="profileUser != null" class="flex flex-col w-full p-4">

                    <!-- PROFILE INFO -->
                    <div class="flex items-center self-center justify-between w-full">
                        <button @click.prevent="like(profileUser.id)" type="button" class="heart">
                            <i class="fa-solid fa-heart"></i>
                            <span class="ml-2">{{ profileUser.totalLikes }}</span>
                        </button>
                        <div class="text-center flex flex-col justify-center self-center items-center">
                            <div class="relative">
                                <img class="w-24 h-246 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500"
                                     :src="avatarUrl"
                                     alt="">
                                <input ref="fileInput" type="file" style="display: none" @change="handleFileChange">
                                <button @click="openFileInput" type="button" class="absolute bottom-0 right-0 text-3xl">
                                    <i class="fa-duotone fa-image"></i>
                                </button>
                            </div>
                            <div class="relative">
                                <input @change.prevent="updateName" v-model="profileName" type="text" :readonly="!readonly" class="mt-4 appearance-none border border-gray-300 rounded-md p-2 bg-transparent border-none text-center" :placeholder="profileName" >
                            </div>
                        </div>
                        <div class="">
                            <button @click.prevent="readonly = !readonly" type="button" class="bg-gray-200 hover:bg-gray-400 dark:bg-gray-600 hover:dark:bg-gray-700 w-10 h-10  rounded ">
                                <i v-if="!readonly" class="fa-sharp fa-light fa-pen"></i>
                                <i v-if="readonly" class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-3 shadow flex flex-col bg-gray-100 dark:bg-gray-900 rounded-lg">
                        <div class="flex justify-between px-4 pt-4">
                            <h1><span class="mr-2"><i class="fa-solid fa-chart-mixed"></i></span> {{ $t('Statistics') }}</h1>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-gray-200 dark:bg-gray-700 text-center p-4">
                                    <p class="text-[12px]">{{ $t('Total winnings') }}</p>
                                    <p class="text-2xl font-bold">
                                        {{ totalEarnings }}
                                    </p>
                                </div>
                                <div class="bg-gray-200 dark:bg-gray-700 text-center p-4">
                                    <p class="text-[12px]">{{ $t('Total bets') }}</p>
                                    <p class="text-2xl font-bold">{{ totalBets }}</p>
                                </div>
                                <div class="bg-gray-200 dark:bg-gray-700 text-center p-4">
                                    <p class="text-[12px]">{{ $t('Total bet') }}</p>
                                    <p class="text-2xl font-bold">{{ sumBets }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="py-3 text-center">
                        <p>ingressou em {{ profileUser.dateHumanReadable }}</p>
                    </div>

                </div>
            </div>
            <div v-if="isLoadingProfile" class="flex flex-col w-full h-full">
                <div role="status" class="absolute -translate-x-1/2 -translate-y-1/2 top-2/4 left-1/2">
                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/><path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/></svg>
                    <span class="sr-only">{{ $t('Loading') }}...</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { RouterLink, useRoute } from "vue-router";
import { sidebarStore } from "@/Stores/SideBarStore.js";
import { Modal } from 'flowbite';
import { useAuthStore } from "@/Stores/Auth.js";
import { useToast } from "vue-toastification";
import { useRouter } from 'vue-router';
import { ref } from 'vue';   


import DropdownDarkLight from "@/Components/UI/DropdownDarkLight.vue";
import LanguageSelector from "@/Components/UI/LanguageSelector.vue";
import WalletBalance from "@/Components/UI/WalletBalance.vue";
import HttpApi from "@/Services/HttpApi.js";
import MakeDeposit from "@/Components/UI/MakeDeposit.vue";
import {useSettingStore} from "@/Stores/SettingStore.js";
import {searchGameStore} from "@/Stores/SearchGameStore.js";
import CassinoGameCard from "@/Pages/Cassino/Components/CassinoGameCard.vue";

export default {
    props: ['simple'],
    components: {CassinoGameCard, MakeDeposit, WalletBalance, LanguageSelector, DropdownDarkLight, RouterLink },
    data() {
        return {
            isLoadingLogin: false,
            isLoadingRegister: false,
            isReferral: false,
            modalAuth: null,
            modalRegister: null,
            modalProfile: null,
            typeInputPassword: 'password',
            readonly: false,
            profileUser: null,
            loginForm: {
                email: '',
                password: '',
            },
            registerForm: {
                name: '',
                email: '',
                cpf: '',
                password: '',
                password_confirmation: '',
                reference_code: '',
                term_a: false,
                agreement: false,
            },
            avatarUrl: '/assets/images/profile.jpg',
            isLoadingProfile: false,
            profileName: '',
            sumBets: 0,
            totalBets: 0,
            totalEarnings: 0,
            showSearchMenu: false,
            games: null,
            searchTerm: '',
            isLoadingSearch: true,
        }
    },
    setup() {
    const isVisible = ref(true);   
    const router = useRouter();    

    function closeBanner() {
      isVisible.value = false;     
    }
  
    return {
	  router,
      isVisible,
      closeBanner,
    };
    },
    computed: {
        searchGameDataStore() {
            return searchGameStore();
        },
        searchGameMenu() {
            const search = searchGameStore();
            return search.getSearchGameStatus;
        },
        sidebarMenuStore() {
            return sidebarStore();
        },
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
        userData() {
            const authStore = useAuthStore();
            return authStore.user;
        },
        setting() {
            const authStore = useSettingStore();
            return authStore.setting;
        }
    },
    unmounted() {

    },
    mounted() {
        /*
        * $targetEl: required
        * options: optional
        */
        this.modalProfile = new Modal(document.getElementById('modalProfileEl'), {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-700 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30',
            closable: false,
            onHide: () => {

            },
            onShow: () => {

            },
            onToggle: () => {

            }
        });

        /*
        * $targetEl: required
        * options: optional
        */
        this.modalAuth = new Modal(document.getElementById('modalElAuth'), {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-700 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-30',
            closable: false,
            onHide: () => {

            },
            onShow: () => {

            },
            onToggle: () => {

            }
        });

        /*
       * $targetEl: required
       * options: optional
       */
        this.modalRegister = new Modal(document.getElementById('modalElRegister'), {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-700 bg-opacity-50 dark:bg-opacity-80 fixed inset-0 z-40',
            closable: false,
            onHide: () => {

            },
            onShow: () => {

            },
            onToggle: () => {

            }
        });
    },
    methods: {
        toggleSearch: function() {
            this.searchGameDataStore.setSearchGameToogle();
        },
        redirectSocialTo: function() {
            return '/auth/redirect/google'
        },
        like: async function(id) {
            const _this = this;
            const _toast = useToast();
            await HttpApi.post('/profile/like/' + id, {})
                .then(response => {

                    _this.getProfile();
                    _toast.success(_this.$t(response.data.message));
                })
                .catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        _toast.error(`${value}`);
                    });
                });
        },
        updateName: async function(event) {
            const _this = this;
            _this.isLoadingProfile = true;

            await HttpApi.post('/profile/updateName', { name: _this.profileName })
                .then(response => {
                    _this.isLoadingProfile = false;
                })
                .catch(error => {
                    const _this = this;
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {

                    });
                    _this.isLoadingProfile = false;
                });
        },
        togglePassword: function() {
            if(this.typeInputPassword === 'password') {
                this.typeInputPassword = 'text';
            }else{
                this.typeInputPassword = 'password';
            }
        },
        loginSubmit: function(event) {
            const _this = this;
            const _toast = useToast();
            _this.isLoadingLogin = true;
            const authStore = useAuthStore();

            HttpApi.post('auth/login', _this.loginForm)
                .then(async response => {
                    await new Promise(r => {
                        setTimeout(() => {
                            authStore.setToken(response.data.access_token);
                            authStore.setUser(response.data.user);
                            authStore.setIsAuth(true);

                            _this.loginForm = {
                                email: '',
                                password: '',
                            }

                            _this.modalAuth.toggle();
                            _toast.success(_this.$t('You have been authenticated, welcome!'));

                            _this.isLoadingLogin = false;
                        }, 1000)
                    });
                })
                .catch(error => {
                    const _this = this;
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        _toast.error(`${value}`);
                    });
                    _this.isLoadingLogin = false;
                });
        },
        registerSubmit: async function(event) {
            const _this = this;
            const _toast = useToast();
            _this.isLoadingRegister = true;

            const authStore = useAuthStore();
            await HttpApi.post('auth/register', _this.registerForm)
                .then(response => {
                    if(response.data.access_token !== undefined) {
                        authStore.setToken(response.data.access_token);
                        authStore.setUser(response.data.user);
                        authStore.setIsAuth(true);

                        _this.registerForm = {
                            name: '',
                            email: '',
                            password: '',
                            password_confirmation: '',
                            reference_code: '',
                            term_a: false,
                            agreement: false,
                        }

                        _this.modalRegister.toggle();
                        _this.router.push({ name: 'profileDeposit' });
                        _toast.success(_this.$t('Your account has been created successfully'));
                    }

                    _this.isLoadingRegister = false;
                })
                .catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        _toast.error(`${value}`);
                    });
                    _this.isLoadingRegister = false;
                });
        },
        logoutAccount: function() {
            const authStore = useAuthStore();
            const _toast = useToast();

            HttpApi.post('auth/logout', {})
                .then(response => {
                    authStore.logout();
                    this.router.push({ name: 'home' });

                    _toast.success(this.$t('You have been successfully disconnected'));
                })
                .catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        console.log(value);
                        //_toast.error(`${value}`);
                    });
                });
        },
        hideLoginShowRegisterToggle: function() {
            this.modalAuth.toggle();
            this.modalRegister.toggle();
        },
        toggleMenu: function() {
            this.sidebarMenuStore.setSidebarToogle();
        },
        loginToggle: function() {
            this.modalAuth.toggle();
        },
        registerToggle: function() {
            this.modalRegister.toggle();
        },
        profileToggle: function() {
            this.modalProfile.toggle();
        },
        openFileInput() {
            this.$refs.fileInput.click();
        },
        async handleFileChange(event) {
            const file = event.target.files[0];
            const formData = new FormData();
            formData.append('avatar', file);

            const reader = new FileReader();
            reader.onload = () => {
                this.avatarUrl = reader.result;
            };
            reader.readAsDataURL(file);

            await HttpApi.post('/profile/upload-avatar', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }).then(response => {
                console.log('Avatar atualizado com sucesso', response.data);
            })
                .catch(error => {
                    console.error('Erro ao atualizar avatar', error);
                });
        },
        getProfile: async function() {
            const _this = this;
            _this.isLoadingProfile = true;

            await HttpApi.get('/profile/')
                .then(response => {
                    _this.sumBets = response.data.sumBets;
                    _this.totalBets = response.data.totalBets;
                    _this.totalEarnings = response.data.totalEarnings;

                    const user = response.data.user;

                    if(user?.avatar != null) {
                        _this.avatarUrl = '/storage/'+user.avatar;
                    }

                    _this.profileName = user.name;
                    _this.profileUser = user;
                    _this.isLoadingProfile = false;
                })
                .catch(error => {
                    const _this = this;
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {

                    });
                    _this.isLoadingProfile = false;
                });
        },
        getSearch: async function() {
            const _this = this;

            await HttpApi.get('/search/games?searchTerm='+this.searchTerm)
                .then(response => {
                    _this.games = response.data.games;
                    _this.isLoadingSearch = false;
                })
                .catch(error => {
                    const _this = this;
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {

                    });
                    _this.isLoadingSearch = false;
                });
        },
        clearData: async function() {
            this.searchTerm = '';
            await this.getSearch();
        }
    },
    async created() {
        if(this.isAuthenticated) {

            await this.getProfile();
        }
    },
    watch: {
        searchTerm(newValue, oldValue) {
            this.getSearch();
        },
        async searchGameMenu(newValue, oldValue) {

            await this.getSearch();
            this.showSearchMenu = !this.showSearchMenu;
        },
    },
	
	
};
</script>

<style scoped>
.fechar-indique {
    color: white;
    font-weight: 800;
    width: 100px;
    cursor: pointer;
    font-size: 15px;
}
.fechar-indique:hover{
	color: #02163A;
	}
	
.container-form{
	width: 27% !important;
	background-color: #000C20;
	overflow: auto;
	}
	
.input-email, .input-senha{
		height: 45px;
		width: 87%;
		padding: 8px 16px;
		background-color: #5c5c5c;
		color: #fff !important;
		border-radius: 5px;
		margin: 0 auto;
	}
	
	
	.esqueceu-senha{
		margin-left: 60%;
    	font-size: 12px;
    	color: #9b9797;
	}
	
	.ui-button-blue{
		background-image: none;
    	
	}
	
	.botao-submit{
		margin-left: 22px;
    	width: 87% !important;
	}

	
	.criar-conta{
	font-size: 12px;
	text-align: center;
	
	}
	
	.criar-conta span{
		color:#03c6fc;
		margin-left: 4px;
		
	}
	.criar-conta span:hover{
		text-decoration: underline;
	}
	
	
	.botao-olho{
		font-size: 13px;
		margin-right: 23px;
	}
	
	.logotipo-form {
    width: 56%;
    text-align: center;
    margin: 0 auto;
	}
	
	
	
	
	
	
	
	
	
	
	
	@media screen and ( max-width: 480px){
	
	.text-indique{
		font-size:11px !important;
	}
	.fechar-indique{
		width: 31px;
		}
	.botao-resgatar{
		font-size: 13px !important;
		}
		
	.container-form{
		width: 76% !important;
		overflow: auto;
		}
	
	}
</style>
