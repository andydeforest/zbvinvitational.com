import { defineStore } from 'pinia';
import { Facebook, Instagram, Linkedin } from 'lucide-vue-next';

export const useNavStore = defineStore('nav', {
  state: (): { links: NavItem[]; social: SocialItem[] } => ({
    links: [
      {
        href: '/',
        label: 'Home'
      },
      {
        href: '/about',
        label: 'About'
      },
      {
        href: '/gallery',
        label: 'Gallery'
      },
      {
        href: '/shop',
        label: 'Shop'
      },
      {
        href: '/donors',
        label: 'Donors'
      },
      {
        href: '/faq',
        label: 'FAQ'
      },
      {
        href: '/contact',
        label: 'Contact'
      }
    ],
    social: [
      {
        icon: Facebook,
        href: 'https://www.facebook.com/profile.php?id=100069280300320&mibextid=LQQJ4d',
        title: 'Facebook'
      },
      {
        icon: Instagram,
        href: 'https://instagram.com/zbvinvitational',
        title: 'Instagram'
      },
      {
        icon: Linkedin,
        href: 'https://www.linkedin.com/company/zbv-invitational/',
        title: 'LinedIn'
      }
    ]
  })
});
