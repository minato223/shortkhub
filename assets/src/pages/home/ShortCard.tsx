import { CardItem, CardItemProps } from '@/components/shared/CardItem';
import React from 'react'

interface ShortCardProps extends CardItemProps {}
const ShortCard = ({
    title,
    description,
    image,
    url
}: ShortCardProps) => {
  return (
    <CardItem title={title} description={description} image={image} url={url} />
  )
}

export default ShortCard