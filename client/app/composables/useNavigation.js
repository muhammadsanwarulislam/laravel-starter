export const useNavigation = () => {
  const scrollToSection = (targetId) => {
    const currentPath = useRoute().path;
    
    if (currentPath !== '/') {
      navigateToHomeWithHash(targetId);
    } else {
      scrollToHomeSection(targetId);
    }
  };

  const navigateToHomeWithHash = (targetId) => {
    const router = useRouter();
    
    router.push({
      path: '/',
      hash: `#${targetId}`
    }).then(() => {
      setTimeout(() => {
        scrollToHomeSection(targetId);
      }, 300);
    });
  };

  const scrollToHomeSection = (targetId) => {
    const element = document.getElementById(targetId);
    if (element) {
      const offset = 70; 
      const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
      const offsetPosition = elementPosition - offset;
      
      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  };

  return { scrollToSection };
};