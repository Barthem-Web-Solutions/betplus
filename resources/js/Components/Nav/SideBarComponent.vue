<template>
    <aside :class="[
              sidebar === true ? 'translate-x-0' : '-translate-x-full',
              //isAuthenticated ? 'top-[65px]' : 'top-[115px]'
            ]"
           class="fixed top-[0px] md:top-[106px] left-0 z-50 w-64 w-full-mobile h-screen transition-transform -translate-x-full sm:translate-x-0 sidebar-color custom-side-shadow"
           aria-label="Sidebar">
        <div style="background: linear-gradient(0deg, #000c20,#021b46, #000c20);" class="h-full pb-4 overflow-y-auto sidebar-color px-1">

            <ul>
                <li v-if="isAuthenticated" class="my-4 flex justify-center items-center">
                    <div class="flex justify-between w-full gap-4">
                        <div class="w-full flex justify-center items-center">
                            <button  @click.prevent="$router.push('/profile/affiliate')" style="background-color: #03c6fc;" class="promo-register transition duration-700 text-sm ease-in-out font-bold rounded-md py-4 w-[240px]">
                                <div class="flex justify-center text-center flex-row items-center">
                                    <p class="text-white font-bold pr-2 text-lg">Ganhe R$ 300,00 grátis</p>
                                    <p class="text-white font-bold text-md">💥</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </li>
                <li v-if="!isAuthenticated" class="my-4 flex justify-center items-center">
                    <button @click.prevent="$router.push('/register')"
                            type="button"
                            style="background-color: #03c6fc;"
                            class="promo-register transition duration-700 text-sm ease-in-out font-bold rounded-md py-4 w-[240px]">
                        <div class="flex justify-center text-center self-center items-center">
                            <div class="flex justify-center text-center flex-row items-center">
                                    <p class="text-white font-bold pr-2 text-lg">Ganhe R$ 15,00 grátis</p>
                                    <p class="text-white font-bold text-md">💥</p>
                            </div>
                        </div>
                    </button>
                </li>
            </ul>
            <div class="border-t-2 border-[#fdffff1a]">

                <div class="p-4">
                    <div class="flex jutify-start py-4">
                        <p class="font-bold text-white text-lg">CASSINO</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 mb-4" v-if="isLoading">
                        <div v-for="i in 10" class="flex flex-col h-20 max-w-sm rounded-xl animate-pulse bg-gray-100 dark:bg-gray-700">

                        </div>
                    </div>

                    <div v-else class="flex flex-col px-2">
                        <a @click="$router.push('/casino/provider/all/category/todos')" class="menu-link cursor-pointer">
                            <svg data-v-a21eb579="" fill="currentColor" height="21px" stroke="currentColor" viewBox="0 0 140.599 140.599" width="21px" xmlns="http://www.w3.org/2000/svg" lined="false"><g fill="currentColor" stroke-width="0"></g><g fill="currentColor" stroke-linecap="round" stroke-linejoin="round"></g><g fill="currentColor"><g><path d="M132.861,56.559c-4.27,0-7.742,3.473-7.742,7.741c0,1.893,0.685,3.626,1.815,4.973l-15.464,15.463 c-2.754,2.754-4.557,1.857-4.027-2l0.062-0.445c0.528-3.857-1.39-4.876-4.286-2.273l-0.531,0.479 c-2.898,2.603-5.828,1.609-6.544-2.219l-5.604-29.964c-0.717-3.828-2.129-3.886-3.156-0.129l-7.023,25.689 c-1.025,3.757-2.295,3.677-2.834-0.181L71.93,33.674c3.488-0.751,6.111-3.856,6.111-7.566c0-4.268-3.473-7.741-7.741-7.741 c-4.269,0-7.742,3.473-7.742,7.741c0,3.709,2.625,6.815,6.112,7.566l-5.592,40.019c-0.539,3.857-1.809,3.938-2.835,0.181 l-7.023-25.69c-1.027-3.757-2.44-3.699-3.156,0.129l-5.605,29.964c-0.716,3.828-3.645,4.82-6.543,2.219l-0.533-0.479 c-2.897-2.604-4.816-1.586-4.287,2.272l0.061,0.445c0.529,3.858-1.274,4.753-4.028,2L13.667,69.273 c1.132-1.347,1.816-3.08,1.816-4.973c0-4.269-3.473-7.741-7.741-7.741C3.473,56.559,0,60.032,0,64.3 c0,4.269,3.473,7.742,7.742,7.742c0.478,0,0.942-0.05,1.396-0.132l10.037,33.949c1.104,3.734,3.534,9.637,7.161,11.055 c8.059,3.153,24.72,5.318,43.964,5.318c19.245,0,35.905-2.165,43.965-5.318c3.626-1.418,6.058-7.32,7.161-11.055l10.037-33.949 c0.453,0.083,0.918,0.132,1.396,0.132c4.268,0,7.739-3.473,7.739-7.742C140.6,60.032,137.127,56.559,132.861,56.559z M11.103,66.708c-0.685,0.954-1.761,1.605-2.994,1.714c-0.121,0.011-0.243,0.019-0.367,0.019c-2.284,0-4.142-1.857-4.142-4.142 c0-2.284,1.858-4.141,4.142-4.141c2.283,0,4.141,1.857,4.141,4.141C11.883,65.2,11.592,66.031,11.103,66.708z M66.159,26.109 c0-2.283,1.858-4.141,4.142-4.141c2.283,0,4.143,1.857,4.143,4.141c0,1.892-1.276,3.488-3.014,3.981 c-0.359,0.102-0.737,0.16-1.129,0.16s-0.769-0.058-1.128-0.16C67.436,29.596,66.159,28,66.159,26.109z M70.301,115.405 l-15.36-15.361l15.36-15.36l15.359,15.359L70.301,115.405z M132.861,68.442c-0.125,0-0.248-0.008-0.369-0.019 c-1.231-0.109-2.309-0.76-2.993-1.714c-0.488-0.68-0.779-1.51-0.779-2.409c0-2.284,1.856-4.141,4.142-4.141 c2.282,0,4.142,1.857,4.142,4.141C137.001,66.583,135.143,68.442,132.861,68.442z M60.036,100.046l10.27-10.27l10.269,10.27 l-10.269,10.27L60.036,100.046z" fill="currentColor"></path></g></g></svg>
                            <span class="text-[14px] font-bold">Todos os Jogos</span>
                        </a>
                        
                        <a @click="$router.push('/casino/provider/all/category/slots')" class="menu-link cursor-pointer">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 640 512" width="21px" xmlns="http://www.w3.org/2000/svg"><path d="M220.7 7.468C247.3-7.906 281.4 1.218 296.8 27.85L463.8 317.1C479.1 343.8 470 377.8 443.4 393.2L250.5 504.5C223.9 519.9 189.9 510.8 174.5 484.2L7.468 194.9C-7.906 168.2 1.218 134.2 27.85 118.8L220.7 7.468zM143.8 277.3C136.9 303.2 152.3 329.1 178.3 336.9C204.3 343.9 230.1 328.5 237.9 302.5L240.3 293.6C240.4 293.3 240.5 292.9 240.6 292.5L258.4 323.2L246.3 330.2C239.6 334 237.4 342.5 241.2 349.2C245.1 355.9 253.6 358.1 260.2 354.3L308.4 326.5C315.1 322.6 317.4 314.1 313.5 307.4C309.7 300.8 301.2 298.5 294.5 302.3L282.5 309.3L264.7 278.6C265.1 278.7 265.5 278.8 265.9 278.9L274.7 281.2C300.7 288.2 327.4 272.8 334.4 246.8C341.3 220.8 325.9 194.1 299.9 187.1L196.1 159.6C185.8 156.6 174.4 163.2 171.4 174.3L143.8 277.3z" fill="currentColor"></path><path d="M324.1 499L459.4 420.9C501.3 396.7 515.7 343.1 491.5 301.1L354.7 64.25C356.5 64.08 358.2 64 360 64H584C614.9 64 640 89.07 640 120V456C640 486.9 614.9 512 584 512H360C346.4 512 333.8 507.1 324.1 499V499zM579.8 135.7C565.8 123.9 545.3 126.2 532.9 138.9L528.1 144.2L523.1 138.9C510.6 126.2 489.9 123.9 476.4 135.7C460.7 149.2 459.9 173.1 473.9 187.6L522.4 237.6C525.4 240.8 530.6 240.8 533.9 237.6L582 187.6C596 173.1 595.3 149.2 579.8 135.7H579.8z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="text-[14px] font-bold">Jogos slot</span>
                        </a>
                        
                        <a @click="$router.push('/casino/provider/all/category/ao-vivo')" class="menu-link cursor-pointer">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 640 512" width="21px" xmlns="http://www.w3.org/2000/svg"><path d="M591.1 192l-118.7 0c4.418 10.27 6.604 21.25 6.604 32.23c0 20.7-7.865 41.38-23.63 57.14l-136.2 136.2v46.37C320 490.5 341.5 512 368 512h223.1c26.5 0 47.1-21.5 47.1-47.1V240C639.1 213.5 618.5 192 591.1 192zM479.1 376c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S493.2 376 479.1 376zM96 200c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1s23.1-10.75 23.1-23.1S109.3 200 96 200zM352 248c13.25 0 23.1-10.75 23.1-23.1s-10.75-23.1-23.1-23.1S328 210.8 328 224S338.8 248 352 248zM224 328c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1c13.25 0 23.1-10.75 23.1-23.1S237.3 328 224 328zM224 200c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1s23.1-10.75 23.1-23.1S237.3 200 224 200zM224 72c-13.25 0-23.1 10.75-23.1 23.1s10.75 23.1 23.1 23.1c13.25 0 23.1-10.75 23.1-23.1S237.3 72 224 72z" fill="currentColor"></path><path d="M447.1 224c0-12.56-4.782-25.13-14.35-34.76l-174.9-174.9C249.1 4.784 236.5 0 223.1 0C211.4 0 198.9 4.784 189.2 14.35L14.35 189.2C4.784 198.9-.0011 211.4-.0011 223.1c0 12.56 4.786 25.18 14.35 34.8l174.9 174.9c9.626 9.563 22.19 14.35 34.75 14.35c12.56 0 25.13-4.782 34.75-14.35l174.9-174.9C443.2 249.1 447.1 236.6 447.1 224zM96 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S109.3 248 96 248zM224 376c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1c13.25 0 23.1 10.75 23.1 23.1S237.3 376 224 376zM224 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S237.3 248 224 248zM224 120c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1c13.25 0 23.1 10.75 23.1 23.1S237.3 120 224 120zM352 248c-13.25 0-23.1-10.75-23.1-23.1s10.75-23.1 23.1-23.1s23.1 10.75 23.1 23.1S365.3 248 352 248z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="text-[14px] font-bold">Cassino ao vivo</span>
                        </a>
                        
                        <a @click="$router.push('/games/play/8680/9946')" class="menu-link cursor-pointer">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 512 512" width="21px" xmlns="http://www.w3.org/2000/svg" lined="false"><path d="M256 256C176.625 256 64 378.75 64 456.25C64 491.125 90.75 512 135.75 512C184.625 512 216.875 486.875 256 486.875C295.5 486.875 327.875 512 376.25 512C421.25 512 448 491.125 448 456.25C448 378.75 335.375 256 256 256Z" fill="currentColor"></path><path d="M193.5 222.625C224.375 214.5 239.875 172.625 228 129.25C221.5 105.375 208 86.25 192 75V0L128 80C113 97.75 107.5 127.75 116 158.75C127.875 202.125 162.5 230.75 193.5 222.625ZM318.5 222.625C349.5 230.75 384.125 202.125 396 158.75C404.5 127.75 399 97.75 384 80L320 0V75C304 86.25 290.5 105.375 284 129.25C272.125 172.625 287.625 214.5 318.5 222.625ZM108.75 243.375C101.5 219.375 83.875 201.5 64 194.875V128L18.5 190.375C6.5 206.875 0 226.75 0 247.125C0 254.001 1.125 261.25 3.25 268.625C13.625 303.25 45.75 325.75 74.875 318.75S119.125 278.001 108.75 243.375ZM493.5 190.375L448 128V195C428.125 201.5 410.5 219.375 403.25 243.375C392.875 278.001 408 311.75 437.125 318.75S498.375 303.25 508.75 268.625C510.875 261.375 512 254.125 512 247.125C512 226.75 505.5 206.875 493.5 190.375Z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="text-[14px] font-bold">Fortune Tiger</span>
                        </a>
                        
                        <a @click="$router.push('/games/play/12050/12559')" class="menu-link cursor-pointer">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 512 512" width="21px" xmlns="http://www.w3.org/2000/svg"><path d="M192 93.68C192 59.53 221 0 256 0C292 0 320 59.53 320 93.68V400L377.6 443.2C381.6 446.2 384 450.1 384 456V497.1C384 505.7 377.7 512 369.1 512C368.7 512 367.4 511.8 366.1 511.5L256 480L145.9 511.5C144.6 511.8 143.3 512 142 512C134.3 512 128 505.7 128 497.1V456C128 450.1 130.4 446.2 134.4 443.2L192 400L192 93.68z" fill="currentColor"></path><path d="M192 320L21.06 376.1C10.7 380.4 0 372.7 0 361.8V305.1C0 294.4 5.347 284.4 14.25 278.5L192 160V320zM320 160L497.8 278.5C506.7 284.4 512 294.4 512 305.1V361.8C512 372.7 501.3 380.4 490.9 376.1L320 319.1V160z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="text-[14px] font-bold">Aviator</span>
                        </a>
                    </div>
                </div>
            </div>
            <ul class="font-medium mb-[200px] border-t-2 border-[#fdffff1a]">
                <div class="p-4">
                    <li class="">
                        <a @click="$router.push('/support')" href="#" class="l-5 flex items-center w-full p-2 text-white font-bold transition duration-700 rounded-lg group dark:text-gray-400 dark:hover:text-white">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 640 512" width="21px" xmlns="http://www.w3.org/2000/svg"><path d="M640 191.1v191.1c0 35.25-28.75 63.1-64 63.1h-32v54.24c0 7.998-9.125 12.62-15.5 7.873l-82.75-62.12L319.1 447.1C284.7 447.1 256 419.2 256 383.1v-31.98l96-.002c52.88 0 96-43.12 96-95.99V128h128C611.3 128 640 156.7 640 191.1z" fill="currentColor"></path><path d="M352 0H64C28.75 0 0 28.75 0 63.1V256C0 291.2 28.75 320 64 320l32 .0098v54.25c0 7.998 9.125 12.62 15.5 7.875l82.75-62.12L352 319.9c35.25 .125 64-28.68 64-63.92V63.1C416 28.75 387.3 0 352 0z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="ml-3">Suporte ao vivo</span>
                        </a>
                    </li>
                    <li class="">
                        <a @click="$router.push('/support')" href="#" class="l-5 flex items-center w-full p-2 text-white font-bold transition duration-700 rounded-lg group dark:text-gray-400 dark:hover:text-white">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 512 512" width="21px" xmlns="http://www.w3.org/2000/svg"><path d="M342.5 214.7C342.6 214.6 342.4 214.8 342.5 214.7l128.1-128.1c12.5-12.5 12.5-32.75 0-45.25s-32.75-12.5-45.25 0L297.3 169.5c-.0742 .0742 .0742-.0762 0 0C317.1 178.1 333 194.9 342.5 214.7zM169.5 297.3C169.4 297.4 169.6 297.2 169.5 297.3l-128.1 128.1c-12.5 12.5-12.5 32.75 0 45.25C47.63 476.9 55.81 480 64 480s16.38-3.125 22.62-9.375l128.1-128.1c.0742-.0742-.0742 .0762 0 0C194.9 333 178.1 317.1 169.5 297.3zM342.5 297.3C342.4 297.2 342.6 297.4 342.5 297.3c-9.463 19.78-25.43 35.74-45.21 45.21c.0742 .0762-.0742-.0742 0 0l128.1 128.1C431.6 476.9 439.8 480 448 480s16.38-3.125 22.62-9.375c12.5-12.5 12.5-32.75 0-45.25L342.5 297.3zM86.63 41.38c-12.5-12.5-32.75-12.5-45.25 0s-12.5 32.75 0 45.25L169.5 214.7c.0742 .0742-.0762-.0742 0 0c9.463-19.78 25.43-35.74 45.21-45.21c-.0742-.0762 .0742 .0742 0 0L86.63 41.38z" fill="currentColor"></path><path d="M214.7 169.5C227.2 163.5 241.2 160 256 160s28.76 3.51 41.29 9.502c.0742-.0762-.0742 .0742 0 0l115.5-115.6C369.5 20.26 315.2 0 256 0S142.5 20.26 99.2 53.95L214.7 169.5C214.8 169.6 214.6 169.4 214.7 169.5zM169.5 297.3C163.5 284.8 160 270.8 160 256s3.51-28.76 9.502-41.29c-.0762-.0742 .0742 .0742 0 0L53.95 99.2C20.26 142.5 0 196.8 0 256s20.26 113.5 53.95 156.8L169.5 297.3C169.6 297.2 169.4 297.4 169.5 297.3zM458.1 99.2l-115.6 115.5c-.0742 .0742 .0762-.0742 0 0C348.5 227.2 352 241.2 352 256s-3.51 28.76-9.502 41.29c.0762 .0742-.0742-.0742 0 0l115.6 115.5C491.7 369.5 512 315.2 512 256S491.7 142.5 458.1 99.2zM297.3 342.5C284.8 348.5 270.8 352 256 352s-28.76-3.51-41.29-9.502c-.0742 .0762 .0742-.0742 0 0l-115.5 115.6C142.5 491.7 196.8 512 256 512s113.5-20.26 156.8-53.95L297.3 342.5C297.2 342.4 297.4 342.6 297.3 342.5z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="ml-3">Central de ajuda</span>
                        </a>
                    </li>
                    <li class="">
                        <a @click="$router.push('/support')" href="#" class="l-5 flex items-center w-full p-2 text-white font-bold transition duration-700 rounded-lg group dark:text-gray-400 dark:hover:text-white">
                            <svg data-v-a21eb579="" height="21px" viewBox="0 0 512 512" width="21px" xmlns="http://www.w3.org/2000/svg"><path d="M320 96C328.8 96 336 103.2 336 112C336 120.8 328.8 128 320 128H192C183.2 128 176 120.8 176 112C176 103.2 183.2 96 192 96H320zM276.1 230.3C282.7 231.5 292.7 233.5 297.1 234.7C307.8 237.5 314.2 248.5 311.3 259.1C308.5 269.8 297.5 276.2 286.9 273.3C283 272.3 269.5 269.7 265.1 268.1C252.9 267.1 242.1 268.7 236.5 271.6C230.2 274.4 228.7 277.7 228.3 279.7C227.7 283.1 228.3 284.3 228.5 284.7C228.7 285.2 229.5 286.4 232.1 288.2C238.2 292.4 247.8 295.4 261.1 299.7L262.8 299.9C274.9 303.6 291.1 308.4 303.2 317.3C309.9 322.1 316.2 328.7 320.1 337.7C324.1 346.8 324.9 356.8 323.1 367.2C319.8 386.2 307.2 399.2 291.4 405.9C286.6 407.1 281.4 409.5 276.1 410.5V416C276.1 427.1 267.1 436.1 255.1 436.1C244.9 436.1 235.9 427.1 235.9 416V409.6C226.4 407.4 213.1 403.2 206.1 400.5C204.4 399.9 202.9 399.4 201.7 398.1C191.2 395.5 185.5 384.2 189 373.7C192.5 363.2 203.8 357.5 214.3 361C216.3 361.7 218.5 362.4 220.7 363.2C230.2 366.4 240.9 370 246.9 371C259.7 373 269.6 371.7 275.7 369.1C281.2 366.8 283.1 363.8 283.7 360.3C284.4 356.3 283.8 354.5 283.4 353.7C283.1 352.8 282.2 351.4 279.7 349.6C273.8 345.3 264.4 342.2 250.4 337.9L248.2 337.3C236.5 333.8 221.2 329.2 209.6 321.3C203 316.8 196.5 310.6 192.3 301.8C188.1 292.9 187.1 283 188.9 272.8C192.1 254.5 205.1 241.9 220 235.1C224.1 232.9 230.3 231.2 235.9 230V223.1C235.9 212.9 244.9 203.9 256 203.9C267.1 203.9 276.1 212.9 276.1 223.1L276.1 230.3z" fill="currentColor"></path><path d="M144.6 24.88C137.5 14.24 145.1 0 157.9 0H354.1C366.9 0 374.5 14.24 367.4 24.88L320 96H192L144.6 24.88zM332.1 136.4C389.7 172.7 512 250.9 512 416C512 469 469 512 416 512H96C42.98 512 0 469 0 416C0 250.9 122.3 172.7 179 136.4C183.9 133.3 188.2 130.5 192 128H320C323.8 130.5 328.1 133.3 332.1 136.4V136.4zM235.9 224V230C230.3 231.2 224.1 232.9 220 235.1C205.1 241.9 192.1 254.5 188.9 272.8C187.1 283 188.1 292.9 192.3 301.8C196.5 310.6 203 316.8 209.6 321.3C221.2 329.2 236.5 333.8 248.2 337.3L250.4 337.9C264.4 342.2 273.8 345.3 279.7 349.6C282.2 351.4 283.1 352.8 283.4 353.7C283.8 354.5 284.4 356.3 283.7 360.3C283.1 363.8 281.2 366.8 275.7 369.1C269.6 371.7 259.7 373 246.9 371C240.9 370 230.2 366.4 220.7 363.2C218.5 362.4 216.3 361.7 214.3 361C203.8 357.5 192.5 363.2 189 373.7C185.5 384.2 191.2 395.5 201.7 398.1C202.9 399.4 204.4 399.9 206.1 400.5C213.1 403.2 226.4 407.4 235.9 409.6V416C235.9 427.1 244.9 436.1 255.1 436.1C267.1 436.1 276.1 427.1 276.1 416V410.5C281.4 409.5 286.6 407.1 291.4 405.9C307.2 399.2 319.8 386.2 323.1 367.2C324.9 356.8 324.1 346.8 320.1 337.7C316.2 328.7 309.9 322.1 303.2 317.3C291.1 308.4 274.9 303.6 262.8 299.9L261.1 299.7C247.8 295.4 238.2 292.4 232.1 288.2C229.5 286.4 228.7 285.2 228.5 284.7C228.3 284.3 227.7 283.1 228.3 279.7C228.7 277.7 230.2 274.4 236.5 271.6C242.1 268.7 252.9 267.1 265.1 268.1C269.5 269.7 283 272.3 286.9 273.3C297.5 276.2 308.5 269.8 311.3 259.1C314.2 248.5 307.8 237.5 297.1 234.7C292.7 233.5 282.7 231.5 276.1 230.3V224C276.1 212.9 267.1 203.9 255.1 203.9C244.9 203.9 235.9 212.9 235.9 224L235.9 224z" fill="currentColor" opacity="0.4"></path></svg>
                            <span class="ml-3">Indique um amigo</span>
                        </a>
                    </li>
                </div>
            </ul>
        </div>

    </aside>
</template>

<script>
import {onMounted} from "vue";
import { sidebarStore } from "@/Stores/SideBarStore.js";
import { RouterLink } from "vue-router";
import HttpApi from "@/Services/HttpApi.js";
import {useToast} from "vue-toastification";
import {useAuthStore} from "@/Stores/Auth.js";
import {useSettingStore} from "@/Stores/SettingStore.js";
import {missionStore} from "@/Stores/MissionStore.js";

export default {
    props: [],
    components: { RouterLink },
    data() {
        return {
            sidebar: false,
            isLoading: true,
            categories: [],
            sportsCategories: [],
            modalMission: null,
            setting: null,
        }
    },
    setup(props) {
        onMounted(() => {

        });

        return {};
    },
    computed: {
        sidebarMenuStore() {
            return sidebarStore()
        },
        sidebarMenu() {
            const sidebar = sidebarStore()
            return sidebar.getSidebarStatus;
        },
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
    },
    mounted() {
    },
    methods: {
        toggleMenu() {
            this.sidebarMenuStore.setSidebarToogle();
        },
        toggleMissionModal: function() {
            const missionDataStore = missionStore();
            missionDataStore.setMissionToogle();
        },
        getCasinoCategories: function() {
            const _this = this;
            const _toast = useToast();
            _this.isLoading = true;

            HttpApi.get('categories')
                .then(response => {
                    _this.categories = response.data.categories;
                    _this.isLoading = false;
                })
                .catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        _toast.error(`${value}`);
                    });
                    _this.isLoading = false;
                });
        },
        getSetting: function() {
            const _this = this;
            const settingStore = useSettingStore();
            const settingData = settingStore.setting;

            if(settingData) {
                _this.setting = settingData;
            }
        },
    },
    created() {
        this.getCasinoCategories();
        this.getSetting();
    },
    watch: {
        sidebarMenu(newVal, oldVal) {
            this.sidebar = newVal;
        }
    },
};
</script>

<style scoped>

</style>
