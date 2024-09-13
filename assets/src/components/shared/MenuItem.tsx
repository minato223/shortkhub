import React from "react";

interface MenuItemProps {
  icon: React.ReactElement;
  title: string;
}
const MenuItem = ({ icon, title }: MenuItemProps) => {
  return (
    <li className="flex items-center gap-2 py-2 px-3 bg-blue-50 cursor-pointer w-full text-blue-900 font-medium rounded-sm">
      {icon}
      {title}
    </li>
  );
};

export default MenuItem;
