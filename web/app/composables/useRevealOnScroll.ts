import { onBeforeUnmount, onMounted } from 'vue'

export function useRevealOnScroll() {
  let observer: IntersectionObserver | null = null

  onMounted(() => {
    const elements = document.querySelectorAll<HTMLElement>('[data-reveal]')

    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible')
            observer?.unobserve(entry.target)
          }
        })
      },
      {
        threshold: 0.18
      }
    )

    elements.forEach((element) => observer?.observe(element))
  })

  onBeforeUnmount(() => {
    observer?.disconnect()
  })
}
