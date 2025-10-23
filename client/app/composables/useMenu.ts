import { ref, readonly } from 'vue';

const menuGroups = [
  {
    title: "User Management",
    items: [
      {
        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-theme dark:text-gray" viewBox="0 0 640 640" fill="currentColor"><path d="M320 80C377.4 80 424 126.6 424 184C424 241.4 377.4 288 320 288C262.6 288 216 241.4 216 184C216 126.6 262.6 80 320 80zM96 152C135.8 152 168 184.2 168 224C168 263.8 135.8 296 96 296C56.2 296 24 263.8 24 224C24 184.2 56.2 152 96 152zM0 480C0 409.3 57.3 352 128 352C140.8 352 153.2 353.9 164.9 357.4C132 394.2 112 442.8 112 496L112 512C112 523.4 114.4 534.2 118.7 544L32 544C14.3 544 0 529.7 0 512L0 480zM521.3 544C525.6 534.2 528 523.4 528 512L528 496C528 442.8 508 394.2 475.1 357.4C486.8 353.9 499.2 352 512 352C582.7 352 640 409.3 640 480L640 512C640 529.7 625.7 544 608 544L521.3 544zM472 224C472 184.2 504.2 152 544 152C583.8 152 616 184.2 616 224C616 263.8 583.8 296 544 296C504.2 296 472 263.8 472 224zM160 496C160 407.6 231.6 336 320 336C408.4 336 480 407.6 480 496L480 512C480 529.7 465.7 544 448 544L192 544C174.3 544 160 529.7 160 512L160 496z"/></svg>',
        name: "User Manager",
        subItems: [
          { name: "User", path: "/users/list", description: "Manage registered users" }
        ]
      }
    ]
  },
  {
    title: "System Settings",
    items: [
      {
        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-theme dark:text-gray" viewBox="0 0 640 640" fill="currentColor"><path d="M320 112C264.6 112 216 160.6 216 216C216 271.4 264.6 320 320 320C375.4 320 424 271.4 424 216C424 160.6 375.4 112 320 112zM96 184C135.8 184 168 216.2 168 256C168 295.8 135.8 328 96 328C56.2 328 24 295.8 24 256C24 216.2 56.2 184 96 184zM0 512C0 441.3 57.3 384 128 384C140.8 384 153.2 385.9 164.9 389.4C132 426.2 112 474.8 112 528L112 544C112 555.4 114.4 566.2 118.7 576L32 576C14.3 576 0 561.7 0 544L0 512zM521.3 576C525.6 566.2 528 555.4 528 544L528 528C528 474.8 508 426.2 475.1 389.4C486.8 385.9 499.2 384 512 384C582.7 384 640 441.3 640 512L640 544C640 561.7 625.7 576 608 576L521.3 576zM472 256C472 216.2 504.2 184 544 184C583.8 184 616 216.2 616 256C616 295.8 583.8 328 544 328C504.2 328 472 295.8 472 256zM160 528C160 439.6 231.6 368 320 368C408.4 368 480 439.6 480 528L480 544C480 561.7 465.7 576 448,576L192,576C174.3,576,160,561.7,160,544L160,528z"/></svg>',
        name: "Settings",
        subItems: [
          { name: "Language", path: "/settings/language", description: "Manage languages" },
          { name: "UI Translations", path: "/settings/translations", description: "Manage UI translations" },
          { name: "Profile", path: "/settings/profile", description: "Manage profile" }
        ],
      },
    ],
  },
];

export function getMenu() {
  const getMenuData = ref(menuGroups);
  return {
    menuGroups: readonly(getMenuData),
  };
}