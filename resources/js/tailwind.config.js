export default {
  content: ['./resources/**/*.blade.php', './resources/**/*.js'],
  theme: {
    extend: {
        // To be changed to mark brand fonts if there is any
      fontFamily: {
        syne: ['Syne', 'sans-serif'],
        dm:   ['DM Sans', 'sans-serif'],
      },
      // to be changed to matvh brand colours incase there is any.
      colors: {
        cream:      '#F5F2EC',
        deep:       '#0D1117',
        forest:     '#1B4332',
        sage:       '#2D6A4F',
        mint:       '#52B788',
        'pale-mint':'#D8F3DC',
        amber:      '#F4A261',
      },
      borderRadius: {
        sm:  '14px',
        md:  '22px',
        lg:  '32px',
        xl:  '44px',
      },
      backdropBlur: {
        glass: '24px',
      },
      animation: {
            'ping-slow': 'ping 3s cubic-bezier(0, 0, 0.2, 1) infinite',
            'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        },
    },
  },
  plugins: [],
}
