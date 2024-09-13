import MenuItem from '@/components/shared/MenuItem'
import { LayoutGrid } from 'lucide-react'
import React from 'react'

const HomeMenuItem = () => {
  return (
    <MenuItem icon={<LayoutGrid className="h-4 w-4" />} title="All Shortcust" />
  )
}

export default HomeMenuItem