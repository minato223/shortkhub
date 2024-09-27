import AppFolderIcon from '@/assets/svg/AppFolderIcon'
import React from 'react'

interface EmptyProps {
    message?: string
}
const Empty = ({message = "No project found"}: EmptyProps) => {
  return (
    <div className='flex flex-col items-center justify-center gap-4'>
        <AppFolderIcon/>
        <p className="text-center text-[12px] text-gray-400">{message}</p>
    </div>
  )
}

export default Empty