import Layout from "@/views/layouts/main";
import {Metadata} from "next";
import View from '@/views/home/index';

export const metadata: Metadata = {
    title: 'Home'
};

const Page = async () => {
    const props = {
        text: 'text'
    }

    return (
        <Layout>
            <View {...props} />
        </Layout>
    )
}

export default Page;
